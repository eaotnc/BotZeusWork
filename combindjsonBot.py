import re
import json
import io
import json
def convert(name):
    s1 = re.sub('(.)([A-Z][a-z]+)', r'\1_\2', name)
    return re.sub('([a-z0-9])([A-Z])', r'\1_\2', s1).upper()

Thai = []
English= []
CombindedWord= dict()
with open('EThaiWordresult.txt', 'r',encoding="utf-8")  as file :
        for line in file:
            Thai.append(line.replace('\n',''))

with open('EnglishWord.txt', 'r',encoding="utf-8")  as file :
        for num, line in enumerate(file, 1):
            print(num)
            word=line.replace('\n','')
            English.append(word)
            word=word.replace('-','_')
            word=word.replace('  ',' ')
            word=word.replace(' ','_')
            CombindedWord[convert(word)] = {"th":Thai[num-1],"en":English[num-1]}
# print(Thai)
# print(English)
print(CombindedWord)

with open('combinded.json', 'w') as fp:
    json.dump(CombindedWord, fp)
 
# with io.open('combinded.json', 'w', encoding='utf-8') as f:
#     f.write(json.dumps(data, ensure_ascii=False))