@extends('errors.layout')

@php($isThai = app()->getLocale() === 'th')

@section('code', '500')
@section('title', $isThai ? 'ระบบมีปัญหา' : 'Something went wrong')
@section('message', $isThai ? 'ระบบฝั่งเรามีปัญหา กรุณาลองใหม่อีกครั้ง หากกำลังจองอยู่ ยังไม่มีการตัดเงิน' : 'Our side is having a problem. Please try again in a moment; if you were booking, nothing was charged.')
