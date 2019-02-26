// TODO: translate
'.$translate["KEY"].'
$translate["KEY"]
<?=$translate["KEY"]?>
case 1: $type_name = $translate["KEY"]; break;
<?=warning_translate_message()?>
<?=waring_language_switcher()?>
[ก-๙]

1. git clone https://git.devnss.com/kmitl-reg/registrar.git -b BRANCH_NAME --depth 1
2. reopen file with encoding Windows 874
3. เอา text คำแปลไปทำเป็น json ที่ลิงค์ https://reg-trans.devnss.com/helper/toJSON.html
4. เอาไฟล์ json ที่ได้ตั้งชื่อเหมือนชื่อไฟล์แต่เป็น .lang.json แทน แล้วเอาไปไว้คู่กับไฟล์ php ที่จะแก้
5. เพิ่ม code ลงไปหลัง require_once ข้างบนของไฟล์

require_once("../translate.php"); // ../ ตามจำนวน folder ถ้า 2 ชั้นก็เป็น ../../

$translate = init_translate("./FILE_NAME.lang.json")

6. แทน text ภาษาไทยให้ตรงกับคำที่แปลมาด้วย key จาก json





วิธี ดึงคำภาษาไทยจากไฟล์ text.php
1.รัน py findThai.py 
2. ได้ภาษาไทยมา เอาไปใส่ google แปลภาษาเอาเป็นไฟล
3. ได้ไฟล์มา เอามารวมกับภาษาไทย เพื่อตรวจสอบคำแปล โดนการ คลุมดำ ภาษาอังกิต ทั้งหมดแล้ว กด 
  shift + ctrl + alt 
4. จากนั้น crl +d ทีละบรรทัด ในไฟลภาษาไทย แล้ว กด crl +v เพื่อวางที่ละคำที่ละบรรทัด

ขึ้นตอนการสร้าง json
1. find : and replace all with   :{\n "th":"thai",\n"en":"english"\n}\n},\n{