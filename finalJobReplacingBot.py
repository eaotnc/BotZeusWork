
# #! python
import sys
import io
import json
import pprint
import re

with io.open('resultCombinded.json', 'r',encoding="utf-8")as jsonFile:
    jsonObject= json.load(jsonFile)

keyJsons=[]

for json in jsonObject:
    for subjson in json:
        objectBuild={ 
            "key":subjson,
            "th": json[subjson]['th'],
            "en":json[subjson]['en'],
        }
        keyJsons.append(objectBuild)
    
def sort_by_age(d):
    print('d',d)
    return len(d['key'])
keyJsons=sorted(keyJsons, key=sort_by_age,reverse=True)
print(keyJsons) 


'.$translate["KEY"].'
for keyJson in keyJsons:
    replaceText= '<?=$translate["'+keyJson['key']+'"]?>'
    replaceSingleQuote= '\'.$translate["'+keyJson['key']+'"].\''
    replaceDoubleQuote= '\".$translate["'+keyJson['key']+'"].\"'

    print(keyJson['en'])
    print(replaceText)
    with io.open('ReplacedWork.php', 'r',encoding="utf-8")  as file :
        filedata = file.read()
        replaced = re.sub('(?!\'.*)'+keyJson['th']+'(?=.*\')', replaceSingleQuote, filedata)
    with io.open('ReplacedWork.php', 'w',encoding="utf-8") as file:
        file.write(replaced)    
        file.close()


    with io.open('ReplacedWork.php', 'r',encoding="utf-8")  as file :
        filedata = file.read()
        replaced = re.sub('(?!".*)'+keyJson['th']+'(?=.*")', replaceDoubleQuote, filedata)
    with io.open('ReplacedWork.php', 'w',encoding="utf-8") as file:
        file.write(replaced)    
        file.close()

    with io.open('ReplacedWork.php', 'r',encoding="utf-8")  as file :
        filedata = file.read()
        replaced = re.sub(keyJson['th'], replaceText, filedata)
    with io.open('ReplacedWork.php', 'w',encoding="utf-8") as file:
        file.write(replaced)    
        file.close()


