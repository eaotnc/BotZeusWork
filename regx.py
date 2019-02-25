import re
import io

with io.open('text.php', 'r',encoding="utf-8")  as file :
    filedata = file.read()
    replaced = re.sub('(?!".*)ดับเบิลโควต(?=.*")', 'เอี่ยว', filedata)

with io.open('text.php', 'w',encoding="utf-8") as file:
    file.write(replaced)  

    # (?<!")title+(?!")
    # (?!".*)apple(?=.*")