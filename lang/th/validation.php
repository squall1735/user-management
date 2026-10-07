<?php

return [
    'confirmed' => 'การยืนยัน:attributeไม่ตรงกัน',
    'current_password' => 'รหัสผ่านไม่ถูกต้อง',
    'email' => ':attributeต้องเป็นอีเมลที่ถูกต้อง',
    'max' => [
        'string' => ':attributeต้องมีความยาวไม่เกิน :max ตัวอักษร',
    ],
    'min' => [
        'string' => ':attributeต้องมีความยาวอย่างน้อย :min ตัวอักษร',
    ],
    'password' => [
        'letters' => ':attributeต้องมีตัวอักษรอย่างน้อย 1 ตัว',
        'mixed' => ':attributeต้องมีตัวพิมพ์ใหญ่และตัวพิมพ์เล็กอย่างน้อยอย่างละ 1 ตัว',
        'numbers' => ':attributeต้องมีตัวเลขอย่างน้อย 1 ตัว',
        'symbols' => ':attributeต้องมีอักขระพิเศษอย่างน้อย 1 ตัว',
        'uncompromised' => ':attributeนี้เคยรั่วไหลออกสู่สาธารณะ กรุณาเลือกรหัสผ่านอื่น',
    ],
    'required' => 'กรุณากรอก:attribute',
    'required_with' => 'กรุณากรอก:attribute เมื่อมีการกรอก :values',
    'same' => ':attributeไม่ตรงกับ:other',
    'string' => ':attributeต้องเป็นข้อความ',
    'unique' => ':attributeนี้ถูกใช้งานแล้ว',

    'attributes' => [
        'name' => 'ชื่อ',
        'email' => 'อีเมล',
        'password' => 'รหัสผ่าน',
        'password_confirmation' => 'ยืนยันรหัสผ่าน',
        'current_password' => 'รหัสผ่านปัจจุบัน',
        'form.name' => 'ชื่อ',
        'form.email' => 'อีเมล',
        'form.password' => 'รหัสผ่าน',
        'form.password_confirmation' => 'ยืนยันรหัสผ่าน',
    ],
];
