n=int(input())
a=0
print(a,end=" ")
b=1
print(b,end=" ")

for i in range(n-2):
    c=a+b
    a=b
    b=c
    print(c, end=" ")