@extends('errors.layout')

@php($isThai = app()->getLocale() === 'th')

@section('code', '419')
@section('title', $isThai ? 'หน้าหมดอายุ' : 'Page expired')
@section('message', $isThai ? 'หน้านี้เปิดค้างไว้นานเกินไป กรุณาย้อนกลับแล้วลองใหม่' : 'This page sat open too long. Please go back and try again.')
