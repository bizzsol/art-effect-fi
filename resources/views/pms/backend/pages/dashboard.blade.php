@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style>
  .avatar-130 {
    border-radius: 10% !important;
    object-fit: contain !important;
  }
  .future-services { margin-bottom: 45px; }
  .iq-fancy-box { box-shadow: 0 0px 90px 0 rgba(0, 0, 0, .04); position: relative; top: 0; -webkit-transition: all 0.5s ease-out 0s; -moz-transition: all 0.5s ease-out 0s; -ms-transition: all 0.5s ease-out 0s; -o-transition: all 0.5s ease-out 0s; transition: all 0.5s ease-out 0s; padding: 50px 30px; overflow: hidden; position: relative; margin-bottom: 30px; -webkit-border-radius: 0; -moz-border-radius: 0; border-radius: 0; }
  .iq-fancy-box .iq-icon { font-size: 36px; border-radius: 90px; display: inline-block; height: 86px; width: 86px; margin-bottom: 15px; line-height: 86px; text-align: center; color: #ffffff; background: #089bab; -webkit-transition: all .5s ease-out 0s; -moz-transition: all .5s ease-out 0s; -ms-transition: all .5s ease-out 0s; -o-transition: all .5s ease-out 0s; transition: all .5s ease-out 0s; }
  .iq-fancy-box:hover { box-shadow: 0 44px 98px 0 rgba(0, 0, 0, .12); top: -8px; }
  .iq-fancy-box .fancy-content h4 { z-index: 9; position: relative; padding-bottom: 5px }
  .iq-fancy-box .fancy-content p { margin-bottom: 0 }
  .iq-fancy-box .future-img i { font-size: 45px; color: #089bab; }
  .feature-effect-box { box-shadow: 0px 7px 22px 0px rgba(0, 0, 0, 0.06); padding: 10px 15px; margin-bottom: 30px; position: relative; top: 0; -webkit-transition: all 0.3s ease-in-out; -o-transition: all 0.3s ease-in-out; -ms-transition: all 0.3s ease-in-out; -webkit-transition: all 0.3s ease-in-out; }
  .feature-effect-box:hover { top: -10px }
  .feature-effect-box .feature-i { margin-right: 10px;
    width: 50px;
    padding: 8px 13px;
    padding-bottom: 6px;
    border-radius: 50%;
    display: inline-block;}
    .feature-effect-box .feature-i i{ font-size: 25px;}
    .feature-effect-box .feature-icon { display: inline-block; }
    .title-box { margin-bottom: 30px;}
    
    body {
      background-color: #f9f9fa
    }

    .flex {
      -webkit-box-flex: 1;
      -ms-flex: 1 1 auto;
      flex: 1 1 auto
    }

    @media (max-width:991.98px) {
      .padding {
        padding: 1.5rem
      }
    }

    @media (max-width:767.98px) {
      .padding {
        padding: 1rem
      }
    }

    

    .project-card {
      background: #fff;
      border-width: 0;
      border-radius: 20px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
      margin-bottom: 1.5rem
    }

    .project-card {
      position: relative;
      display: flex;
      flex-direction: column;
      min-width: 0;
      width: 100%;
      height: 500px;
      word-wrap: break-word;
      background-color: #fff;
      background-clip: border-box;
      border: 1px solid rgba(19, 24, 44, .125);
      border-radius: .25rem
    }

    .project-card-header {
      padding: .75rem 1.25rem;
      margin-bottom: 0;
      background-color: rgba(19, 24, 44, .03);
      border-bottom: 1px solid rgba(19, 24, 44, .125)
    }

    .project-card-header:first-child {
      border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0
    }

    .project-card-footer,
    .project-card-header {
      background-color: transparent;
      border-color: rgba(160, 175, 185, .15);
      background-clip: padding-box
    }
    #contextMenu .item {
      cursor: pointer;
      transition: 1s;
    }
    #contextMenu .item:hover{
      background: #fff5f4;
      transition: 1s;
    }

    .clearfix:after {
      clear: both;
    }

    .clearfix:before,
    .clearfix:after {
      display: table;
      content: " ";
    }

    .panel {
      margin-bottom: 10px;
      background-color: #fff;
      border: 1px solid transparent;
      border-radius: 4px;
      -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
      box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    }

    .panel-footer {
      padding: 10px 15px;
      background-color: #f5f5f5;
      border-top: 1px solid #ddd;
      border-bottom-right-radius: 3px;
      border-bottom-left-radius: 3px;
    }

    .panel-heading {
      height: 100px;
      background-color: turquoise;
      padding: 10px 15px;
      border-bottom: 1px solid transparent;
      border-top-left-radius: 3px;
      border-top-right-radius: 3px;
    }

    .panel-green {
      border: 2px dashed #398439;
    }

    .panel-green .panel-heading {
      background-color: #398439;
    }

    .green {
      color: #398439;
    }

    .blue {
      color: #337ab7;
    }

    .red {
      color: #ce7f7f;
    }

    .panel-primary {
      border: 2px dashed #337ab7;
    }

    .panel-primary .panel-heading {
      background-color: #337ab7;
    }

    .yellow {
      color: #ffcc00;
    }

    .panel-yellow {
      border: 2px dashed #ffcc00;
    }

    .panel-yellow .panel-heading {
      background-color: #ffcc00;
    }

    .panel-red {
      border: 2px dashed #ce7f7f;
    }

    .panel-red .panel-heading {
      background-color: #ce7f7f;
    }

    .huge {
      font-size: 30px;
    }

    .panel-heading {
      color: #fff;
    }

    .pull-left {
      float: left !important;
    }

    .pull-right {
      float: right !important;
    }

    .text-right {
      text-align: right;
    }

    .under-number {
      font-size: 20px;
    }
    .iq-mr--20{
      margin-right: 20px;
    }
  </style>
  @endsection
  @section('main-content')
  @php
  $user = auth()->user();
  @endphp

  <div class="row">
    @include('pms.backend.pages.dashboard-partials.admin-link')
  </div>
  @endsection
  @section('page-script')
  @endsection
