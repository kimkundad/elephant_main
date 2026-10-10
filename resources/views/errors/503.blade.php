@extends('errors.layout')

@php($isThai = app()->getLocale() === 'th')

@section('code', '503')
@section('title', $isThai ? 'ปิดปรับปรุงชั่วคราว' : 'Back shortly')
@section('message', $isThai ? 'เว็บไซต์ปิดปรับปรุงชั่วคราว อีกสักครู่จะกลับมาปกติ' : 'The site is down for maintenance and will be back in a few minutes.')
