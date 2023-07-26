<?php
/**
 * Created by Victor Sousa.
 * User: Victor Sousa <victor.sousa.o@gmail.com>
 * Date: 13/02/2019
 * Time: 14:47
 */
?>

@if ($message = Session::get('success'))
    {{Session::forget('success')}}
    <div class="m-alert m-alert--icon alert alert-success" role="alert">
        <div class="m-alert__icon">
            <i class="la la-check"></i>
        </div>
        <div class="m-alert__text">
            <strong>
                {{ $message }}
            </strong>
        </div>
        <div class="m-alert__close">
            <button type="button" class="close" data-close="alert" aria-label="Hide"></button>
        </div>
    </div>
@endif

@if ($message = Session::get('error'))
    {{Session::forget('error')}}
    <div class="m-alert m-alert--icon alert alert-danger" role="alert">
        <div class="m-alert__icon">
            <i class="la la-close"></i>
        </div>
        <div class="m-alert__text">
            <strong>
                {{ $message }}
            </strong>
        </div>
        <div class="m-alert__close">
            <button type="button" class="close" data-close="alert" aria-label="Hide"></button>
        </div>
    </div>
@endif

@if ($message = Session::get('warning'))
    {{Session::forget('warning')}}
    <div class="m-alert m-alert--icon alert alert-warning" role="alert">
        <div class="m-alert__icon">
            <i class="la la-warning"></i>
        </div>
        <div class="m-alert__text">
            <strong>
                {{ $message }}
            </strong>
        </div>
        <div class="m-alert__close">
            <button type="button" class="close" data-close="alert" aria-label="Hide"></button>
        </div>
    </div>
@endif

@if ($message = Session::get('info'))
    {{Session::forget('info')}}
    <div class="m-alert m-alert--icon alert alert-info" role="alert">
        <div class="m-alert__icon">
            <i class="la la-info"></i>
        </div>
        <div class="m-alert__text">
            <strong>
                {{ $message }}
            </strong>
        </div>
        <div class="m-alert__close">
            <button type="button" class="close" data-close="alert" aria-label="Hide"></button>
        </div>
    </div>
@endif