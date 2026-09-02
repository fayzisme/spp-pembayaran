<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
{{-- <script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.min.js"></script> --}}
<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<!-- Page JS -->
    
<script type="text/javascript">
    $('.show_confirm').click(function(event) {
        var form = $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        swal({
                title: 'Hapus Data',
                text: 'Apakah Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'Ya, hapus',
                        value: true,
                        visible: true,
                        className: 'btn btn-danger',
                        closeModal: true
                    },
                    cancel: {
                        text: 'Batal',
                        value: null,
                        visible: true,
                        className: 'btn btn-secondary',
                        closeModal: true
                    }
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
    });
</script>

<script>
    document.getElementById('logout-button').addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi!',
            text: 'Apakah Anda yakin ingin keluar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, keluar',
            // 
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
</script>


<script>
    function updateUsername() {
        const nis = document.getElementById('nis').value;
        document.getElementById('username').value = nis;
        document.getElementById('username2').value = nis;
    }

    function updatePassword() {
        const password = document.getElementById('password').value;
        document.getElementById('password2').value = password;
    }
</script>
<script>
    $(document).ready(function() {
        $('#tahun_ajaran_mulai').on('input', function() {
            var startYear = parseInt($(this).val());
            if (!isNaN(startYear) && $(this).val().length >= 4) {
                $('#tahun_ajaran_selesai').val(startYear + 1);
            } else {
                $('#tahun_ajaran_selesai').val('');
            }
        });

        $('#tarif').on('input', function() {
            var value = $(this).val();
            value = value.replace(/[^,\d]/g, '');
            $(this).val(formatRupiah(value, 'Rp '));
        });

        $('#tarifForm').on('submit', function(event) {
            var value = $('#tarif').val();
            value = value.replace(/[^,\d]/g, '');
            $('#tarif').val(value);
        });
        
        $('#editTarifForm').on('submit', function(event) {
            var value = $('#tarif').val();
            value = value.replace(/[^,\d]/g, '');
            $('#tarif').val(value);
        });

        $('#jumlah_transaksi2').on('input', function() {
            var value = $(this).val();
            value = value.replace(/[^,\d]/g, '');
            $('#jumlah_transaksi').val(value);
            $(this).val(formatRupiah(value, 'Rp '));
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }
    });
</script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
