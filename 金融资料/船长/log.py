#!/usr/bin/python
# -*- coding: UTF-8 -*-
import math


#低位
mystr="低位=>"
print mystr.decode('utf-8')
x=input("low:")

#高位
mystr="高位=>"
print mystr.decode('utf-8')
y=input("high:")


#回调支撑目标=低位^*高位^(1-n)
#反弹压力目标=高位^*低位^(1-n)
#x=1974.38 # 低位
#y=5178.18 # 高位

m1=0.125
m2=0.236
m3=0.382
m4=0.5
m5=0.618
m6=0.809
m7=0.875
 

#回调目标计算
z1=math.pow(x, m1)*math.pow(y, (1-m1)) 
z2=math.pow(x, m2)*math.pow(y, (1-m2))
z3=math.pow(x, m3)*math.pow(y, (1-m3))
z4=math.pow(x, m4)*math.pow(y, (1-m4))
z5=math.pow(x, m5)*math.pow(y, (1-m5))
z6=math.pow(x, m6)*math.pow(y, (1-m6))
z7=math.pow(x, m7)*math.pow(y, (1-m7))

#反弹压力计算
c1=math.pow(y, m1)*math.pow(x, (1-m1)) 
c2=math.pow(y, m2)*math.pow(x, (1-m2))
c3=math.pow(y, m3)*math.pow(x, (1-m3))
c4=math.pow(y, m4)*math.pow(x, (1-m4))
c5=math.pow(y, m5)*math.pow(x, (1-m5))
c6=math.pow(y, m6)*math.pow(x, (1-m6))
c7=math.pow(y, m7)*math.pow(x, (1-m7))

# 换行输出
mystr="回调支撑目标"
print mystr.decode('utf-8')

print "0.125:   ",round(z1,2)
print "0.236:   ",round(z2,2) 
print "0.382:   ",round(z3,2) 
print "0.5:     ",round(z4,2) 
print "0.618:   ",round(z5,2) 
print "0.809:   ",round(z6,2) 
print "0.875:   ",round(z7,2) 

print '---------'
mystr="反弹压力目标"
print mystr.decode('utf-8')

# 换行输出
print "0.125:   ",round(c1,2)
print "0.236:   ",round(c2,2)
print "0.382:   ",round(c3,2)
print "0.5:     ",round(c4,2)
print "0.618:   ",round(c5,2)
print "0.809:   ",round(c6,2)
print "0.875:   ",round(c7,2)

#print '---------'
# 不换行输出
#print x,
#print y,