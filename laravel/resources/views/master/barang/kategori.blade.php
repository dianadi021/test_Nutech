@section('title', 'Fullstack Laravel')

<x-dynamic-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Kategori Barang') }}
        </h2>
    </x-slot>

    <div class="w-full">
        <div class="ml-8 mr-8">
            <div class="py-12">
                <div class="w-full flex">
                    <!-- Modal Controller -->
                    <div x-data="{ isModalOpen: false }">
                        <!-- Button to Open Modal -->
                        <button @click="isModalOpen = true"
                            class="capitalize bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                            tambah
                        </button>

                        <!-- Modal -->
                        <div x-show="isModalOpen"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            style="display: none;">
                            <!-- Modal Content -->
                            <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6"
                                @click.away="isModalOpen = false" @keydown.escape.window="isModalOpen = false">
                                <!-- Modal Header -->
                                <div class="flex justify-between items-center border-b pb-3">
                                    <h2 class="text-lg font-bold">Buat Data Baru</h2>
                                    <button @click="isModalOpen = false" class="text-gray-500 hover:text-gray-700">
                                        &times;
                                    </button>
                                </div>

                                <!-- Modal Body -->
                                <form id="formAddKategori" onsubmit="simpanKategori()">
                                    <div class="mt-4">
                                        <div class="mt-4">
                                            <x-input-label for="name" :value="__('Nama')" />
                                            <x-text-input id="name" class="block mt-1 w-full" type="text"
                                                name="name" :value="old('name')" required autocomplete="name" />
                                            <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2" id="err_name">
                                                <li></li>
                                            </ul>
                                        </div>

                                        <div class="mt-4">
                                            <x-input-label for="description" :value="__('Deskripsi')" />
                                            <x-text-input id="description" class="block mt-1 w-full" type="text"
                                                name="description" :value="old('description')" required autocomplete="username" />
                                            <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2"
                                                id="err_description">
                                                <li></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="mt-6 flex justify-end space-x-2">
                                        <button type="reset" @click="isModalOpen = false"
                                            class="bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300">
                                            Batal
                                        </button>
                                        <button id="btnSimpan" type="submit" onclick="simpanKategori()"
                                            class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <table id="dataTable"></table>
            </div>
        </div>
    </div>

    <script>
        const HOST = window.location.host;
        const tmpURL = HOST.includes("github.io") ? `https://${HOST}` : `http://${HOST}`;
        const $base_url = `${tmpURL}`;

        $(document).ready(async function() {
            // DATA TABLES START
            const dataTableshead = [
                { data: 'name', title: 'Nama' },
                { data: 'description', title: 'Deskripsi' },
                {
                    data: 'actions',
                    title: 'Aksi',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <div class='text-center'>
                                <button class="bg-yellow-500 text-white font-medium px-4 py-2 rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300 btn-edit" data-id="${row.id}">
                                    Edit
                                </button>
                                <button class="bg-red-500 text-white font-medium px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 btn-delete" data-id="${row.id}">
                                    Delete
                                </button>
                            </div>
                        `;
                    }
                }
            ];
            await ContentLoaderDataTable(`${$base_url}/api/kategori-barang`, `#dataTable`, dataTableshead);

            // Event Listener untuk Edit
            $('#dataTable').on('click', '.btn-edit', function () {
                const id = $(this).data('id');
                alert('Edit data dengan ID: ' + id);
            });

            // Event Listener untuk Delete
            $('#dataTable').on('click', '.btn-delete', function () {
                const id = $(this).data('id');
                if (confirm('Yakin ingin menghapus data dengan ID: ' + id + '?')) {
                    alert('Data dengan ID ' + id + ' dihapus!');
                }
            });
            // DATA TABLES END
        });

        function simpanKategori() {
            event.preventDefault();
            $(".hide_notif").each(function() {
                $(this).hide();
            });

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

                    $("#btnSimpan").hide();
                    $("#loadingAjax").show();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                    });

                    $("#_csrf-token").val($('meta[name="csrf-token"]').attr('content'));
                    $("#csrf-token").val($('meta[name="csrf-token"]').attr('content'));

                    $.ajax({
                        url: `${$base_url}/api/kategori-barang`,
                        type: "POST",
                        data: $("#formAddKategori").serialize(),
                        xhrFields: {
                            withCredentials: true
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: async function(callback) {
                            const { messages } = callback;
                            console.log('success', callback);
                            toastr.success(messages, "Success!");

                            $("#btnSimpan").show();
                            $("#loadingAjax").hide();

                            location.reload();
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
                            $("#btnSimpan").show();
                            $("#loadingAjax").hide();
                        },
                    });
                }
            });
        }
    </script>
</x-dynamic-layout>
