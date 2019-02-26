import re
import json
import io
def convert(name):
    s1 = re.sub('(.)([A-Z][a-z]+)', r'\1_\2', name)
    return re.sub('([a-z0-9])([A-Z])', r'\1_\2', s1).upper()

Thai = []
English= []
key=[]
json=[]
with open('EThaiWordresult.txt', 'r',encoding="utf-8")  as file :
        for line in file:
            Thai.append(line.replace('\n',''))

with open('EnglishWord.txt', 'r',encoding="utf-8")  as file :
        for line in file:
            word=line.replace('\n','')
            English.append(word)
            word=word.replace('-','_')
            word=word.replace('  ',' ')
            word=word.replace(' ','_')
            key.append(convert(word))
            json.append({key:{"en"}})
print(Thai)
print(English)
print(key)

with io.open('key.json', 'w',encoding="utf-8") as file:
    file.write(" ".join(str(x) for x in key))    
    file.close()