#!/usr/bin/python3
 
import pymongo
 
myclient = pymongo.MongoClient("mongodb://localhost:27017/")
mydb = myclient["test"]
mycol = mydb["stocktemp2"]

for x in mycol.find({ "$and": [ { "GN": { "$eq": "5G" } }, { "GN": { "$eq": "军工" } }, { "GN": { "$eq": "创投" } } ] }):
	print(x)