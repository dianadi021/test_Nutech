@section('title', 'Fullstack Laravel')

<x-dynamic-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Logistik Barang Produk') }}
        </h2>
    </x-slot>

    <div class="w-full">
        <div class="ml-8 mr-8">
            <div class="py-12" x-data="{ isModalOpen: false, action: '', idItem: null, nameItem: '', nameDesc: '' }">
                <div class="w-full flex">
                    <!-- Modal Controller -->
                    <div>
                        <!-- Button to Open Modal -->
                        <button @click="isModalOpen = true; action = 'add'; idItem = null; nameItem = ''; nameDesc = ''"
                            class="capitalize bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                            tambah
                        </button>

                        <!-- Modal -->
                        <div id="modal_container"></div>

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
                { data: 'name', title: 'Nama'},
                { data: 'description', title: 'Deskripsi', orderable: false, searchable: false, },
                { data: 'kategori_barang', title: 'Kategori Barang' },
                { data: 'harga_beli', title: 'Harga Beli', render: function (data, type, row) {
                    let formatted = new Intl.NumberFormat("id-ID", {style: "currency", currency: "IDR", minimumFractionDigits: 0}).format(row.harga_beli);
                    return `<p class="capitalize">${formatted}</p>`;
                }},
                { data: 'harga_jual', title: 'Harga Jual', render: function (data, type, row) {
                    let formatted = new Intl.NumberFormat("id-ID", {style: "currency", currency: "IDR", minimumFractionDigits: 0}).format(row.harga_jual);
                    return `<p class="capitalize">${formatted}</p>`;
                }},
                {
                    data: 'actions',
                    title: 'Aksi',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <div class='text-center'>
                                <button @click="isModalOpen = true; action = 'edit'; idItem = ${row.id}; nameItem = '${row.name}'; nameDesc = '${row.description}'" class="bg-yellow-500 text-white font-medium px-4 py-2 rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300 btn-edit" data-id="${row.id}" data-name="${row.name}">
                                    Edit
                                </button>
                                <button class="bg-red-500 text-white font-medium px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 btn-delete" data-id="${row.id}" data-name="${row.name}">
                                    Delete
                                </button>
                            </div>
                        `;
                    }
                }
            ];
            await ContentLoaderDataTable(`${$base_url}/api/products`, `#dataTable`, dataTableshead);

            // Event Listener untuk Edit
            $('#dataTable').on('click', '.btn-edit', function () {
                const idData = $(this).data('id');
                const nameData = $(this).data('name');
            });

            // Event Listener untuk Delete
            $('#dataTable').on('click', '.btn-delete', function (callback) {
                const idData = $(this).data('id');
                const nameData = $(this).data('name');
                Swal.fire({
                    title: "Apakah kamu yakin ingin melanjutkan?",
                    text: `Untuk menghapus ${nameData}`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "Batal",
                    confirmButtonText: "Oke!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        toastr.warning("Sedang diproses, mohon tunggu!", "Peringatan!");

                        $(this).hide();
                        $("#loadingAjax").show();

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                        });

                        $("#_csrf-token").val($('meta[name="csrf-token"]').attr('content'));
                        $("#csrf-token").val($('meta[name="csrf-token"]').attr('content'));

                        $.ajax({
                            url: `${$base_url}/api/products/${idData}`,
                            type: 'DELETE',
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

                                $(this).show();
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
            });
            // DATA TABLES END

            // ONLOAD START
            await PopUpFormModal();
            async function PopUpFormModal() {
                const kategorisData = await GetDataFromAPI(`${$base_url}/api/categories-item`);
                const { datas } = kategorisData;
                let tmpHtmlKategoris = `<option disabled selected>Pilih salah satu...</option>`;

                if (datas) {
                    datas.forEach(function (list) {
                        tmpHtmlKategoris += `<option value='${list.id}' class="capitalize">${list.name}</option>`;
                    });
                }

                const htmlKategoris = `
                <div class="mt-4">
                    <x-input-label for="kategori_barang" :value="__('Kategori Barang')" />
                    <select id="id_kategori_barang" name="id_kategori_barang" class="capitalize mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">${tmpHtmlKategoris}</select>
                </div>
                `;

                const html = `
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
                            <h2 class="text-lg font-bold" x-text="action === 'edit' ? 'Edit Data' : 'Tambah Data'"></h2>
                            <button @click="isModalOpen = false" class="text-gray-500 hover:text-gray-700">
                                &times;
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <form id="formKategori" @submit.prevent="action === 'edit' ? simpanProduk(idItem) : simpanProduk()">
                            <div class="mt-4">
                                <div class="mt-4">
                                    <x-input-label for="name" :value="__('Nama')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text"
                                        name="name" :value="old('name')" required autocomplete="name" x-model="nameItem ? nameItem : ''" />
                                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2" id="err_name">
                                        <li></li>
                                    </ul>
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="description" :value="__('Deskripsi')" />
                                    <x-text-input id="description" class="block mt-1 w-full" type="text"
                                        name="description" :value="old('description')" autocomplete="description" x-model="nameDesc ? nameDesc : ' '" />
                                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2"
                                        id="err_description">
                                        <li></li>
                                    </ul>
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="harga_beli" :value="__('Harga Beli')" />
                                    <x-text-input id="harga_beli" class="block mt-1 w-full convert_to_idr" type="text"
                                        name="harga_beli" :value="old('harga_beli')" autocomplete="harga_beli" x-model="nameDesc ? nameDesc : ' '" />
                                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2"
                                        id="err_harga_beli">
                                        <li></li>
                                    </ul>
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="harga_jual" :value="__('Harga Jual')" />
                                    <x-text-input id="harga_jual" class="block mt-1 w-full convert_to_idr" type="text"
                                        name="harga_jual" :value="old('harga_jual')" autocomplete="harga_jual" x-model="nameDesc ? nameDesc : ' '" readonly />
                                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2"
                                        id="err_harga_jual">
                                        <li></li>
                                    </ul>
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="stok" :value="__('Stok')" />
                                    <x-text-input id="stok" class="block mt-1 w-full input_number_only" type="text"
                                        name="stok" :value="old('stok')" autocomplete="stok" x-model="nameDesc ? nameDesc : ' '" />
                                    <ul class="hide_notif text-sm text-red-600 space-y-1 mt-2"
                                        id="err_stok">
                                        <li></li>
                                    </ul>
                                </div>

                                ${htmlKategoris}
                            </div>

                            <!-- Modal Footer -->
                            <div class="mt-6 flex justify-end space-x-2">
                                <button type="reset" @click="isModalOpen = false"
                                    class="bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300">
                                    Batal
                                </button>
                                <button id="btnSimpan" type="submit" @submit.prevent="action === 'edit' ? simpanProduk(idItem) : simpanProduk()"
                                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                `;

                $("#modal_container").html(html);
                AutoToIDR();
                InputNumberOnly();
                hitungHargaJual();
            }
            // ONLOAD END
        });

        function simpanProduk($idItem = null) {
            event.preventDefault();
            $(".hide_notif").each(function() {
                $(this).hide();
            });

            const $method = ($idItem ? "PUT" : "POST");
            const $endpoint = ($idItem ? `${$base_url}/api/products/${$idItem}` : `${$base_url}/api/products`);

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

                    const $tmpSendDatas = {
                        name: $("#name").val(),
                        description: $("#description").val(),
                        id_kategori_barang: $("#id_kategori_barang").val(),
                        harga_beli: IDRToDecimal($("#harga_beli").val()),
                        harga_jual: IDRToDecimal($("#harga_jual").val()),
                        stok: $("#stok").val()
                    };

                    $.ajax({
                        url: `${$endpoint}`,
                        type: `${$method}`,
                        data: $tmpSendDatas,
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

        function hitungHargaJual() {
            $("#harga_beli").keyup(function () {
                let hargaBeli = $(this).val();
                let tmpHargaBeli = hargaBeli.replace("Rp", "").replace(".", "").replace(" ", "");
                let hargaJual = parseInt(tmpHargaBeli) + (parseInt(tmpHargaBeli) * 30 / 100);
                let formatted = new Intl.NumberFormat("id-ID", {style: "currency", currency: "IDR", minimumFractionDigits: 0}).format(hargaJual);

                $("#harga_jual").val(formatted);
            })
        }
    </script>
</x-dynamic-layout>
