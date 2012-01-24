#Brian Ward
#98110098
#Python program to scrape data from a website, http://mlg.ucd.ie/courses/assign2.htm

#import the necessary library###################################################################
import urllib
import sys
from sgmllib import SGMLParser

#take in the URL
#the variables we will use later
#check that there was a command line argument

if len(sys.argv) < 2:
    print ("A user-specified argument is required")
    sys.exit(1)
    
#the file is read in from the command line

url = sys.argv[1]

#check that the url is http://mlg.ucd.ie/courses/assign2.html   

print "Downloading page from URL", (url)

#access the web page############################################################################
#exception handling if the url is incorrect or/unavailable

try:
#open the connection
    f = urllib.urlopen(url)
except IOError:
    print ("the web page you specified is invalid")
    sys.exit(1)
else:
#now we need to access the url and download the page
#download the content into a variable called page
    page = f.read()
#close connection
    f.close() 
#test that the page was read in
#print page

#parse page information read in###################################################
#create a Custom parser class from http://www.boddie.org.uk/python/HTML.html
class CustomParser(SGMLParser):
    def __init__(self):
        SGMLParser.__init__(self)
        self.data = ""
        #instantiate an array of hyperlinks
        self.hyperlinks = []
        self.descriptions = []
        self.inside_a_element = 0

    def handle_data(self, data):  
        #"Handle the textual 'data'."
        if self.inside_a_element:
            #add the text to the array
            self.descriptions.append(data) 
         
    def start_a(self, attributes):
# take each hyperlink and add it to the array, set the text within
        for name, value in attributes:
            if name == "href":
                self.hyperlinks.append(value)
                self.inside_a_element = 1
                       
    def end_a(self):
        # "Record the end of a hyperlink."
        self.inside_a_element = 0
#"Finished link tag"
        
    def get_descriptions(self):
        #"Return a list of descriptions."
        return self.descriptions
    
    def get_links(self):
#"Return a list of hyperlinks."
        return self.hyperlinks

#create an instance of the custom parser##############################
parser =CustomParser()
#feed the page content to the parser
parser.feed(page)

#print out our results to test our class
#print parser.get_links()
#print parser.get_descriptions()

#get the size of the array
size = len(parser.get_descriptions())
#print size
#iterate through both arrays printing our the required results
for x in range(0,size):
    print "URL of " , parser.get_descriptions()[x], " is ", parser.get_links()[x]




