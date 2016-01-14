import base64
import json
import sys
import json
import urllib
import traceback
import time

try:
	# For Python 3.0 and later
	from urllib.request import urlopen, Request
	from urllib.error import HTTPError
except ImportError:
	# Fall back to Python 2's urllib2
	from urllib2 import urlopen, Request, HTTPError


API_ENDPOINT = 'https://api.twitter.com'
API_VERSION = '1.1'
REQUEST_TOKEN_URL = '%s/oauth2/token' % API_ENDPOINT
REQUEST_RATE_LIMIT = '%s/%s/application/rate_limit_status.json' % \
					 (API_ENDPOINT, API_VERSION)

REQUEST_SEARCH = '%s/%s/search/tweets.json' % \
					 (API_ENDPOINT, API_VERSION)


class ClientException(Exception):
	pass


class Client(object):
	"""This class implements the Twitter's Application-only authentication."""

	def __init__(self, consumer_key, consumer_secret):
		self.consumer_key = consumer_key
		self.consumer_secret = consumer_secret
		self.access_token = ''

	def request(self, url):
		"""Send an authenticated request to the Twitter API."""
		if not self.access_token:
			self.access_token = self._get_access_token()

		request = Request(url)
		request.add_header('Authorization', 'Bearer %s' % self.access_token)
		try:
			response = urlopen(request)
		except HTTPError as e:
			print 'httpERROR'
			#traceback.print_exc()
			print '---Sleep, too many requests...'
			time.sleep(60)
			raise ClientException

		raw_data = response.read().decode('utf-8')
		data = json.loads(raw_data)
		return data

	def rate_limit_status(self, resource=''):
		"""Returns a dict of rate limits by resource."""
		response = self.request(REQUEST_RATE_LIMIT)
		if resource:
			resource_family = resource.split('/')[1]
			return response['resources'][resource_family][resource]
		return response

	def search(self,search_url):
		print 'searching...'
		final_url = REQUEST_SEARCH+search_url
		tweets = self.request(final_url)
		return tweets

	def _get_access_token(self):
		"""Obtain a bearer token."""
		print 'ask token...'
		bearer_token = '%s:%s' % (self.consumer_key, self.consumer_secret)
		encoded_bearer_token = base64.b64encode(bearer_token.encode('ascii'))
		request = Request(REQUEST_TOKEN_URL)
		request.add_header('Content-Type',
						   'application/x-www-form-urlencoded;charset=UTF-8')
		request.add_header('Authorization',
						   'Basic %s' % encoded_bearer_token.decode('utf-8'))

		request_data = 'grant_type=client_credentials'.encode('ascii')
		if sys.version_info < (3,4):
			request.add_data(request_data)
		else:
			request.data = request_data

		response = urlopen(request)
		raw_data = response.read().decode('utf-8')
		data = json.loads(raw_data)
		print 'got token! %s' % data['access_token']
		return data['access_token']

####### --------------------
#RUN

# The consumer secret is an example and will not work for real requests
# To register an app visit https://dev.twitter.com/apps/new
#CONSUMER_KEY = '9Pas5ZRz7sqAzaQfazsgMkj3W'
#CONSUMER_SECRET = 'uXQvvRSrekMEgEIaD1hNvKHfUEdYkUG8jKLbFFCorWHzXP0EIe'

#client = Client(CONSUMER_KEY, CONSUMER_SECRET)

# Pretty print of tweet payload
#resp = client.search('?q=pala&count=100&since_id=687350640637558786')

#print resp

# Show rate limit status for this application
#status = client.rate_limit_status()
#print status['resources']['search']
