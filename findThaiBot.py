import re
import json
import io

with io.open('ReplacedWork.php', 'r',encoding="utf-8")  as file :
        filedata = file.read()
        

my_list = []
match= 1
while match !=None:
    try:
        match=re.search('[ก-๙]+', filedata)
    except:
        break
    if(match!=None):
        find=filedata.find(match.group())
        my_list.append(match.group())  
        filedata = filedata.replace(match.group(), '',1)
        
    
# with open('thai.json', 'w') as outfile:
#     json.dump(my_list, outfile,ensure_ascii=False)
my_list=list(set(my_list))
print(my_list,len(my_list))

with io.open('EThaiWordresult.txt', 'w',encoding="utf-8") as file:
    for thaiword in my_list:
        file.write(thaiword+'\n')   

file.close()