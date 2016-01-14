import tweepy
import datetime
import pymysql.cursors
import traceback
import time
import sys
import json

class TwitterConnectionPool:

    pool = [
        {
            'consumer_key' : '0VhsX6xOwxjU6fSLHGgzXYKIT',
            'consumer_secret' : 'yoaTwcoBL6weHcn4cCEJanV2QJC0XD9OJU4wQkc0CKJgPnaG9s'
        },
        {
            'consumer_key' : 'OrdZi4lkaOaF9LwW4NlUM8mfZ',
            'consumer_secret' : '2h7iaqTEhphvbDnYmIbxIOlgD5agI3OI1gcCIGqqOD0ucbn2zx'
        },
        {
            'consumer_key' : '9Pas5ZRz7sqAzaQfazsgMkj3W',
            'consumer_secret' : 'uXQvvRSrekMEgEIaD1hNvKHfUEdYkUG8jKLbFFCorWHzXP0EIe'
        },
    ]
    
    currentConnection = -1
    
    def nextConnection(self):
        self.currentConnection += 1
        if self.currentConnection > len(self.pool)-1:
            self.currentConnection = 0

        return self.currentConnection

    def getConnection(self):

        ts = None
        while (ts is None):
            try:
                data = self.pool[self.nextConnection()]
                print '---connection %s' % self.currentConnection 

                auth = tweepy.AppAuthHandler(data['consumer_key'], data['consumer_secret'])
 
                ts = tweepy.API(auth, wait_on_rate_limit=False, wait_on_rate_limit_notify=False)

            except Exception as e:
                ts = None
                print '>>> traceback del getConnection <<<'
                traceback.print_exc()
                #print '>>> end of traceback <<<'

        return ts

#**************************************

def doSearch(twPool, ts, url,last_max_id,initial_count):
    count = int(initial_count)
    

    # init variables needed in loop
    todo = True
    next_max_id = 0
    max_id = None
    first_max_id = False
    sleep_for = 60 # sleep for 60 seconds

    # let's start the action
    while(todo):

        try:

            if next_max_id != '0' and last_max_id != '0':
                response = ts.search(q=url, count=100, since_id=last_max_id, max_id=next_max_id)
            elif next_max_id != '0':
                response = ts.search(q=url, count=100, max_id=next_max_id)
            elif last_max_id != '0':
                response = ts.search(q=url, count=100, since_id=last_max_id)
                max_id = last_max_id
            else:
                response = ts.search(q=url, count=100)

            # first query the Twitter API
            #response = ts.search_tweets(tso)
#            response = ts.search(tso.create_search_url())

            # print rate limiting status
            #print( "Current rate-limiting status: %s" % ts.get_metadata()['x-rate-limit-remaining'])

         #   status_data = ts.rate_limit_status()
         #   remaining_tweets = status_data['resources']['search']['/search/tweets']['remaining']
         #   print "Current rate-limiting status: %s" % remaining_tweets

            # check if there are statuses returned and whether we still have work to do
            todo = not len(response) == 0

            new = len(response)
            if new > 0:
                print ('NEWS %s!' % new)

            count += new

            # check all tweets according to their ID
            for tweet in response:
                tweet_id = tweet.id

                # current ID is lower than current next_max_id?
                if (tweet_id < next_max_id) or (next_max_id == 0):
                    next_max_id = tweet_id
                    if first_max_id == False:
                        first_max_id = True
                        max_id = next_max_id
                    next_max_id -= 1 # decrement to avoid seeing this tweet again

            #stats
            #if remaining_tweets == 0:
            #    print '-----tiene zero!!!!!'
            #    ts = twPool.getConnection()

        except Exception as e:
            print '>>> traceback 1 <<<'
            ts = twPool.getConnection()
            traceback.print_exc()

            #if 'status_data' in locals():
            #    epoch = status_data['resources']['search']['/search/tweets']['reset']

            #    print epoch
                #print '>>> fin <<<'
            #    reset = datetime.datetime.fromtimestamp(float(epoch)).strftime('%Y-%m-%d %H:%M:%S')
            #    print reset
            #print '>>> fin2 <<<'
            #sys.exit()

    print url
    #print count
    #print max_id

    if max_id is None:
        max_id = 0

    return {'count': count , 'max_id': max_id}

#**************************************

#RUN

# Connect to the database
connection = pymysql.connect(host='localhost',
                             user='root',
                             password='root',
                             db='db',
                             charset='utf8mb4',
                             database='topranking_ar',
                             cursorclass=pymysql.cursors.DictCursor)

try:
    with connection.cursor() as cursor:
        # Read a single record
        sql = "SELECT id, link, max_id, counts, updated_at FROM tw_shares ORDER BY updated_at ASC"
        cursor.execute(sql)
        result = cursor.fetchall()

    twPool = TwitterConnectionPool()

    try:
        ts = twPool.getConnection()
    except Exception as e:
        print '>>> traceback 2<<<'
        traceback.print_exc()

    for link in result:
        #print link
        if ts:
            resp = doSearch(twPool,ts,link['link'],link['max_id'],link['counts'])
            #print resp
            with connection.cursor() as cursor:
                sql = "UPDATE `tw_shares` set `counts` = %s, `max_id` = %s, `updated_at` = %s where `id` = %s"
                cursor.execute(sql, (resp['count'],resp['max_id'],time.strftime('%Y-%m-%d %H:%M:%S'),link['id']))
                connection.commit()
        else:
            print 'finalizo, fracaso'


finally:
    connection.close()
#insert tw shares






#count = doSearch(ts,'http://tn.com.ar/policiales/los-profugos-tenian-cinco-armas-de-fuego_646892')
#count = doSearch(ts,'http://www.lanacion.com.ar/1861253-cristian-lanatta-y-victor-schillaci-estaban-deshidratados-y-tenian-quemaduras-por-el-sol')
#count = doSearch(ts,'http://www.lanacion.com.ar/1861237-los-profugos-aparecieron-a-1-km-del-lugar-donde-cayo-martin-lanatta-y-en-una-zona-que-habia-sido-rastrillada')
#count = doSearch(ts,'http://www.clarin.com/policiales/Martin-Lanatta-declarar-dispuesto-colaborar_0_1502249998.html')
#count = doSearch(ts,'http://www.clarin.com/sociedad/Victor-Hugo-despedido-Continental-censuraron_0_1502249938.html')