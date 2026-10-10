@extends('errors.layout')

@php($isThai = app()->getLocale() === 'th')

@section('code', '404')
@section('title', $isThai ? 'หน้านี้ไม่มีอยู่' : 'Page not found')
@section('message', $isThai ? 'หน้าที่คุณเปิดอาจถูกย้ายหรือไม่มีอยู่ โปรแกรมทัวร์ทั้งหมดยังอยู่ครบ' : 'The page you were looking for has moved or never existed. The tours are all still here.')
