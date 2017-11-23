#!/usr/bin/python
# -*- coding: UTF-8 -*-
from __future__ import division 
import sys

#赢可以赢多少？例如：1
y=input("win:")
#输会输多少？例如：1
s=input("lose:")
#胜率例如：0.5
p=input("p:")
#y=15
#s=7


#赔率
b=y/s
#赢的概率
#p=0.5
#输的概率
q=1-p

f=(b*p-q)/b

mystr="仓位:"
print mystr.decode('utf-8'),f,"%"

mystr="仓位数:"
print mystr.decode('utf-8'),f*100
