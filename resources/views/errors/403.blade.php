@extends('errors.layout')

@php($isThai = app()->getLocale() === 'th')

@section('code', '403')
@section('title', $isThai ? 'ไม่มีสิทธิ์เข้าถึง' : 'Not allowed')
@section('message', $isThai ? 'คุณไม่มีสิทธิ์เข้าหน้านี้' : 'You do not have access to this page.')
