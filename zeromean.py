#Brian Ward
#98110098
#Python programm to import dat from a csv file and calculate the mean, Standard deviation and zeromean for the data 

#import the csv library###################################################################
import csv
import math

#test
#print("test")

#instantiate a class#######################################################################
class Zeromean:
    def __init__(self, filename):
        #take in the name of the file
        self.file = filename
        self.nested_list = [] 
        self.meanArray = []
        self.standardDeviationArray = []
        self.zeroMeanArray = []
        #check that the filename has been read in
        print (self.file), " is the file to be read in."


#function to take in file name##############################################################    
    def readin(self):  
        #print("test1") 
        holder = open(self.file, 'r')    
        for line in holder.readlines():
            for row in line.split():
                    #print (row), " is the  row" 
                    self.nested_list.append(row)
        #print(self.nested_list)
       
          
#the function to calculate the mean for each row##############################################     
    def calculatemean(self):  
        #print("test2")    
        #print(self.nested_list)
        #for row in self.nested_list
        for row in  self.nested_list:
            #print (row), " is the row"
            counter = 0
            total = 0
            for number in  row.split(','):
                counter = counter + 1
                #print(counter), " is the counter"
                #print (number), " is the number"
                total = total + int (number)
                #print (total), " is the total for this row"
            total = total / counter
            #add the mean for each row to the appropriate array
            self.meanArray.append(total)
            #print (total), " is the mean for this row"
        print(self.meanArray),  "is the mean array"
        
#the function to calculate the mean for each row###############################################        
    def calculateStandardDeviation(self):
       #print("test3_________________________________________") 
        counter1 = 0 
        for row in  self.nested_list:
            #print (row), " is the row"
            counter = 0
            total = 0
            holder = 0
            for number in  row.split(','):
                #print(counter), " is the counter"
                #print (number), " is the number"
                #print (self.meanArray[counter1]), " is the self.meanArray[counter]"
                holder = math.pow((int (number) - int (self.meanArray[counter1])),2)
                counter = counter + 1
                #print (holder), " is the holder"
                total = total + holder
                #print (total), " is the total"
            
            total = total/counter
            #print (total), " is the total"
            total = math.sqrt(total)
            counter1 = counter1 + 1
            self.standardDeviationArray.append(total)
        
        print(self.standardDeviationArray), "is the standard deviation array"
        
#the function to calculate the zeromean for each row############################################### 

    def calculateZeroMean(self):  
        #print("test4_________________________________________") 
        counter = 0   
        for k, row in  enumerate (self.nested_list):
            #print (row), " is the row before manipulation"
            #counter = 0
            self.zeroMeanArray =[]
            for i, number in enumerate (row.split(',')):    
                #print(k)
                #print(self.nested_list[k]),"row k"
                #print(self.meanArray[counter]), " is the meanarray"
                #print(self.standardDeviationArray[counter]), " is the Standard Deviation"
                zero = (int (number) - self.meanArray[counter])/self.standardDeviationArray[counter]
                #print (zero), " is the zero"
                number= zero
                #print (number), " is the number"
                self.zeroMeanArray.append(zero)
            self.nested_list[k]= self.zeroMeanArray
            counter = counter + 1
            #print (row), " is the row of zero tolerances"      
        #print(self.zeroMeanArray)
        #print(self.nested_list),"nested list"
        
#the function to write results to a csv file############################################### #####     

    def writeout(self):
        #print("test5_________________________________________") 
        resultWriter = csv.writer(open('norm.csv','w'))
        #resultWriter.writerow(self.zeroMeanArray)
        for row in self.nested_list:
            #print(row)
            resultWriter.writerow(row)
        
        
###################################################################################################
#Instantiate a member of the Zeromean class
#tester1 = Zeromean('tester.csv')
tester1 = Zeromean('allaml.csv')
#print("____________________________________________________________________")
tester1.readin()
#print("____________________________________________________________________")
tester1.calculatemean()
#print("____________________________________________________________________")
tester1.calculateStandardDeviation()
#print("____________________________________________________________________")
tester1.calculateZeroMean()
#print("____________________________________________________________________")
tester1.writeout()
#print("____________________________________________________________________")

