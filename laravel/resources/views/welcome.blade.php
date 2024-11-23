@extends('layouts.app')

@section('title', 'Fullstack Laravel')

@section('root_container')
    <main class="w-full flex items-center justify-center h-screen">
        <div id="registerSection"
            class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <form id="registerForm" onsubmit="checkForm('registerForm')">
                @csrf
                <input type="hidden" name="_token" id="_csrf-token" />
                <input type="hidden" name="token" id="csrf-token" />

                <div>
                    <x-input-label for="name" :value="__('Nama Lengkap')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                        required autofocus autocomplete="name" />
                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2" id="err_name">
                        <li></li>
                    </ul>
                </div>

                <div class="mt-4">
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')"
                        required autocomplete="username" />
                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2" id="err_username">
                        <li></li>
                    </ul>
                </div>

                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                        required autocomplete="username" />
                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2" id="err_email">
                        <li></li>
                    </ul>
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" />

                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2" id="err_password">
                        <li></li>
                    </ul>
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password" />

                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2" id="err_password_confirmation">
                        <li></li>
                    </ul>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <p class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer"
                        onclick="openSection('loginSection')">
                        {{ __('Sudah terdaftar?') }}
                    </p>

                    <x-primary-button id="btnRegister" class="ms-4">
                        {{ __('Daftar') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
        <div id="loginSection"
            class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <form id="loginForm" onsubmit="checkForm('loginForm')">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2" id="err_email">
                        <li></li>
                    </ul>
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2" id="err_password">
                        <li></li>
                    </ul>
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Ingat password') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4 gap-10">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer" href="{{ route('password.request') }}">
                            {{ __('Lupa password?') }}
                        </a>
                    @endif

                    <p class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer"
                        onclick="openSection('registerSection')">
                        {{ __('Belum terdaftar?') }}
                    </p>

                    <x-primary-button id="btnLogin" class="ms-3">
                        {{ __('Masuk') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const HOST = window.location.host;
        const tempURL = HOST.includes("github.io") ? `https://${HOST}` : `http://${HOST}`;
        const $base_url = `${tempURL}`;
        // ONLOAD START
        $(document).ready(function () {
            $("#loginSection").hide();
            // $("#registerSection").hide();
        })
        // ONLOAD END
    </script>
@endsection

<script>
    function checkForm($section) {
        event.preventDefault();
        $(".hide_notif").each(function () {
            $(this).hide();
        });

        if ($section == "registerForm") {
            Swal.fire({
                title: "Apakah kamu yakin ingin melanjutkan?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Batal",
                confirmButtonText: "Oke!"
            }).then((result) => {
                if (result.isConfirmed) {
                    toastr.warning("Sedang diproses, mohon tunggu!", "Peringatan!");

                    $("#btnRegister").hide();
                    $("#loadingAjax").show();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                    });

                    $("#_csrf-token").val($('meta[name="csrf-token"]').attr('content'));
                    $("#csrf-token").val($('meta[name="csrf-token"]').attr('content'));

                    $.ajax({
                        url: `${$base_url}/api/users`,
                        type: "POST",
                        data: $("#registerForm").serialize(),
                        xhrFields: { withCredentials: true },
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(callback) {
                            const { messages } = callback;
                            console.log('success', callback);
                            toastr.success(messages, "Success!");

                            AjaxLoginRedirect($section, $base_url);
                            $("#btnRegister").show();
                        },
                        error: function(callback) {
                            const { responseJSON } = callback;
                            const { errors, message, messages, datas } = responseJSON;
                            let errorInfo, validator;
                            if (datas) {
                                const { errorInfo: errInfo, validator: validCallback } = datas
                                errorInfo = errInfo;
                                validator = validCallback;
                            }
                            console.log('error', callback);

                            if (errors) {
                                for (let key in errors) {
                                    toastr.error(errors[key][0], "Kesalahan!");
                                    $(`#err_${key}`).show();
                                    $(`#err_${key} li`).html(errors[key][0]);
                                }
                            } else if (message || messages || errorInfo || validator) {
                                const tmpMsg = (validator ? "input data tidak sesuai atau tidak boleh kosong" : (errorInfo ? errorInfo[2] : (messages ? messages : message)));
                                toastr.error(tmpMsg, "Kesalahan!");
                            }
                            $("#btnRegister").show();
                            $("#loadingAjax").hide();
                        },
                    });
                }
            });
        }
        if ($section == "loginForm") {
            Swal.fire({
                title: "Apakah kamu yakin ingin melanjutkan?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Batal",
                confirmButtonText: "Oke!"
            }).then((result) => {
                if (result.isConfirmed) {
                    toastr.warning("Sedang diproses, mohon tunggu!", "Peringatan!");

                    $("#btnLogin").hide();
                    $("#loadingAjax").show();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                    });

                    $("#_csrf-token").val($('meta[name="csrf-token"]').attr('content'));
                    $("#csrf-token").val($('meta[name="csrf-token"]').attr('content'));

                    AjaxLoginRedirect($section, $base_url);
                    $("#btnLogin").show();
                }
            });
        }
    }

    function openSection($section) {
        if ($section === "registerSection") {
            $("#loginSection").hide();
            $("#registerSection").show();
        }
        if ($section === "loginSection") {
            $("#registerSection").hide();
            $("#loginSection").show();
        }
    }
</script>
