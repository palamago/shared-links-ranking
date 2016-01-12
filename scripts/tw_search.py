from TwitterSearch import *
import datetime
import pymysql.cursors

def doSearch(ts,url):
    count = 0;
    try:
        tso = TwitterSearchOrder()
        tso.set_keywords([url])



        # init variables needed in loop
        todo = True
        next_max_id = 0

        # let's start the action
        while(todo):

            # first query the Twitter API
            response = ts.search_tweets(tso)

            # print rate limiting status
            #print( "Current rate-limiting status: %s" % ts.get_metadata()['x-rate-limit-reset'])

            # check if there are statuses returned and whether we still have work to do
            todo = not len(response['content']['statuses']) == 0

            count += len(response['content']['statuses'])

            # check all tweets according to their ID
            for tweet in response['content']['statuses']:
                tweet_id = tweet['id']

                # current ID is lower than current next_max_id?
                if (tweet_id < next_max_id) or (next_max_id == 0):
                    next_max_id = tweet_id
                    next_max_id -= 1 # decrement to avoid seeing this tweet again

            # set lowest ID as MaxID
            tso.set_max_id(next_max_id)

    except TwitterSearchException as e:
        print(e)

    print (url)
    print count

    return count

#**************************************


#RUN

ts = TwitterSearch(
    consumer_key = '9Pas5ZRz7sqAzaQfazsgMkj3W',
    consumer_secret = 'uXQvvRSrekMEgEIaD1hNvKHfUEdYkUG8jKLbFFCorWHzXP0EIe',
    access_token = '138394734-27Vg4aXFcAIO2Z1RRKmmFy5Ch7JJ3Rh3ykUDT3kr',
    access_token_secret = 'D2Zat3XZFaG3uMw7PzFf9soapxJEF7zZtO7dSZoQJcwxo'
)


# Connect to the database
connection = pymysql.connect(host='localhost',
                             user='root',
                             password='root',
                             db='db',
                             charset='utf8mb4',
                             database='topranking',
                             cursorclass=pymysql.cursors.DictCursor)

try:
    with connection.cursor() as cursor:
        # Read a single record
        sql = "SELECT id, link FROM tw_shares"
        cursor.execute(sql)
        result = cursor.fetchall()

    for link in result:
        count = doSearch(ts,link['link'])
        with connection.cursor() as cursor:
            sql = "UPDATE `tw_shares` set `counts` = %s where `id` = %s"
            cursor.execute(sql, (count,link['id']))
            connection.commit()

finally:
    connection.close()
#insert tw shares






#count = doSearch(ts,'http://tn.com.ar/policiales/los-profugos-tenian-cinco-armas-de-fuego_646892')
#count = doSearch(ts,'http://www.lanacion.com.ar/1861253-cristian-lanatta-y-victor-schillaci-estaban-deshidratados-y-tenian-quemaduras-por-el-sol')
#count = doSearch(ts,'http://www.lanacion.com.ar/1861237-los-profugos-aparecieron-a-1-km-del-lugar-donde-cayo-martin-lanatta-y-en-una-zona-que-habia-sido-rastrillada')
#count = doSearch(ts,'http://www.clarin.com/policiales/Martin-Lanatta-declarar-dispuesto-colaborar_0_1502249998.html')
#count = doSearch(ts,'http://www.clarin.com/sociedad/Victor-Hugo-despedido-Continental-censuraron_0_1502249938.html')