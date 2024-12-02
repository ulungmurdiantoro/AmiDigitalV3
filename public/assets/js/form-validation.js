// npm package: jquery-validation
// github link: https://github.com/jquery-validation/jquery-validation

$(function() {
  'use strict';

  // $.validator.setDefaults({
  //   submitHandler: function() {
  //     alert("submitted!");
  //   }
  // });
  $(function() {
    // validate signup form on keyup and submit
    $("#ProdiForm").validate({
      rules: {
        prodi_nama: {
          required: true,
        },
        prodi_jenjang: {
          required: true,
        },
        prodi_jurusan: {
          required: true,
        },
        prodi_fakultas: {
          required: true,
        },
        prodi_akreditasi: {
          required: true,
        },
        akreditasi_kadaluarsa: {
          required: true,
        },
        akreditasi_bukti: {
          required: true,
        },
        password: {
          required: true,
          minlength: 8
        },
        confirm_password: {
          required: true,
          minlength: 5,
          equalTo: "#password"
        },
        terms_agree: "required"
      },
      messages: {
        prodi_nama: "Mohon Masukkan Nama Program Studi",
        prodi_jenjang: "Mohon Pilih Jenjang Program Studi",
        prodi_jurusan: "Mohon Pilih Jurusan Program Studi",
        prodi_fakultas: "Mohon Pilih Fakultas Program Studi",
        prodi_akreditasi: "Mohon Pilih Status Akrediatas Program Studi",
        akreditasi_kadaluarsa: "Mohon Masukkan Tanggal Kadaluarsa Akreditasi Program Studi",
        akreditasi_bukti: "Mohon Masukkan Bukti Akrediatas Program Studi"
      },
      errorPlacement: function(error, element) {
        error.addClass( "invalid-feedback" );

        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        }
        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
          error.insertAfter(element.parent().parent());
        }
        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
          error.appendTo(element.parent().parent());
        }
        else {
          error.insertAfter(element);
        }
      },
      highlight: function(element, errorClass) {
        if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
          $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        }
      },
      unhighlight: function(element, errorClass) {
        if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
          $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
      }
    });
    // validate signup form on keyup and submit
    $("#JurusanForm").validate({
      rules: {
        jurusan_nama: {
          required: true,
        },
      },
      messages: {
        jurusan_nama: "Mohon Masukkan Nama Jurusan",
      },
      errorPlacement: function(error, element) {
        error.addClass( "invalid-feedback" );

        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        }
        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
          error.insertAfter(element.parent().parent());
        }
        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
          error.appendTo(element.parent().parent());
        }
        else {
          error.insertAfter(element);
        }
      },
      highlight: function(element, errorClass) {
        if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
          $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        }
      },
      unhighlight: function(element, errorClass) {
        if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
          $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
      }
    });
    // validate signup form on keyup and submit
    $("#FakultasForm").validate({
      rules: {
        fakultas_nama: {
          required: true,
        },
      },
      messages: {
        fakultas_nama: "Mohon Masukkan Nama Fakultas",
      },
      errorPlacement: function(error, element) {
        error.addClass( "invalid-feedback" );

        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        }
        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
          error.insertAfter(element.parent().parent());
        }
        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
          error.appendTo(element.parent().parent());
        }
        else {
          error.insertAfter(element);
        }
      },
      highlight: function(element, errorClass) {
        if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
          $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        }
      },
      unhighlight: function(element, errorClass) {
        if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
          $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
      }
    });
    // validate signup form on keyup and submit
    $("#PenggunaProdiForm").validate({
      rules: {
          user_id: {
              required: true,
          },
          user_nama: {
              required: true,
          },
          user_jabatan: {
              required: true,
          },
          user_penempatan: {
              required: true,
          },
          user_akses: {
              required: true,
          },
          username: {
              required: true,
          },
          password: {
              required: true,
              minlength: 8
          },
      },
      messages: {
          user_id: "Mohon Masukkan NIP/NIK (ID)",
          user_nama: "Mohon Masukkan Nama Lengkap",
          user_jabatan: "Mohon Masukkan Jabatan",
          user_penempatan: "Mohon Pilih Tanggung Jawab Akun",
          user_akses: "Mohon Pilih Hak Standar Akreditasi",
          username: "Mohon Masukkan Username",
          password: {
              required: "Mohon Masukkan Password",
              minlength: "Password minimal harus 8 karakter"
          },
      },
      errorPlacement: function(error, element) {
          error.addClass("invalid-feedback");
  
          if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
          }
          else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
              error.insertAfter(element.parent().parent());
          }
          else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
              error.appendTo(element.parent().parent());
          }
          else {
              error.insertAfter(element);
          }
      },
      highlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-invalid").removeClass("is-valid");
          }
      },
      unhighlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-valid").removeClass("is-invalid");
          }
      }
    });
    // validate signup form on keyup and submit
    $("#PenggunaProdiFormEdit").validate({
      rules: {
          user_id: {
              required: true,
          },
          user_nama: {
              required: true,
          },
          user_jabatan: {
              required: true,
          },
          user_penempatan: {
              required: true,
          },
          user_akses: {
              required: true,
          },
          username: {
              required: true,
          },
      },
      messages: {
          user_id: "Mohon Masukkan NIP/NIK (ID)",
          user_nama: "Mohon Masukkan Nama Lengkap",
          user_jabatan: "Mohon Masukkan Jabatan",
          user_penempatan: "Mohon Pilih Tanggung Jawab Akun",
          user_akses: "Mohon Pilih Hak Standar Akreditasi",
          username: "Mohon Masukkan Username",
      },
      errorPlacement: function(error, element) {
          error.addClass("invalid-feedback");
  
          if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
          }
          else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
              error.insertAfter(element.parent().parent());
          }
          else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
              error.appendTo(element.parent().parent());
          }
          else {
              error.insertAfter(element);
          }
      },
      highlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-invalid").removeClass("is-valid");
          }
      },
      unhighlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-valid").removeClass("is-invalid");
          }
      }
    });
    // validate signup form on keyup and submit
    $("#PenggunaAuditorForm").validate({
      rules: {
          user_id: {
              required: true,
          },
          user_nama: {
              required: true,
          },
          user_jabatan: {
              required: true,
          },
          user_pelatihan: {
              required: true,
          },
          user_sertfikat: {
              required: false,
          },
          user_sk: {
              required: false,
          },
          username: {
              required: true,
          },
          password: {
              required: true,
              minlength: 8
          },
      },
      messages: {
          user_id: "Mohon Masukkan NIP/NIK (ID)",
          user_nama: "Mohon Masukkan Nama Lengkap",
          user_jabatan: "Mohon Masukkan Jabatan",
          user_pelatihan: "Mohon Masukkan Tahun Pelatihan",
          user_sertfikat: "Mohon Upload Dokumen Pelatihan Auditor Yang Pernah Diikuti",
          user_sk: "Mohon Upload SK",
          username: "Mohon Masukkan Username",
          password: {
              required: "Mohon Masukkan Password",
              minlength: "Password minimal harus 8 karakter"
          },
      },
      errorPlacement: function(error, element) {
          error.addClass("invalid-feedback");
  
          if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
          }
          else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
              error.insertAfter(element.parent().parent());
          }
          else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
              error.appendTo(element.parent().parent());
          }
          else {
              error.insertAfter(element);
          }
      },
      highlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-invalid").removeClass("is-valid");
          }
      },
      unhighlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-valid").removeClass("is-invalid");
          }
      }
    });
    // validate signup form on keyup and submit
    $("#PenggunaAuditorFormEdit").validate({
      rules: {
          user_id: {
              required: true,
          },
          user_nama: {
              required: true,
          },
          user_jabatan: {
              required: true,
          },
          user_pelatihan: {
              required: true,
          },
          username: {
              required: true,
          },
      },
      messages: {
          user_id: "Mohon Masukkan NIP/NIK (ID)",
          user_nama: "Mohon Masukkan Nama Lengkap",
          user_jabatan: "Mohon Masukkan Jabatan",
          user_pelatihan: "Mohon Masukkan Tahun Pelatihan",
          user_sertfikat: "Mohon Upload Dokumen Pelatihan Auditor Yang Pernah Diikuti",
          user_sk: "Mohon Upload SK",
          username: "Mohon Masukkan Username",
          password: {
              required: "Mohon Masukkan Password",
              minlength: "Password minimal harus 8 karakter"
          },
      },
      errorPlacement: function(error, element) {
          error.addClass("invalid-feedback");
  
          if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
          }
          else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
              error.insertAfter(element.parent().parent());
          }
          else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
              error.appendTo(element.parent().parent());
          }
          else {
              error.insertAfter(element);
          }
      },
      highlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-invalid").removeClass("is-valid");
          }
      },
      unhighlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-valid").removeClass("is-invalid");
          }
      }
    });
    // validate signup form on keyup and submit
    $("#PenjadwalanAMIForm").validate({
      rules: {
          prodi: {
              required: true,
          },
          auditor_kode: {
              required: true,
          },
          periode: {
              required: true,
          },
          opening_ami: {
              required: true,
          },
          pengisian_dokumen: {
              required: true,
          },
          deskevaluasion: {
              required: true,
          },
          assessment: {
              required: true,
          },
          tindakan_koreksi: {
              required: true,
          },
          laporan_ami: {
              required: true,
          },
          rtm: {
              required: true,
          },
      },
      messages: {
          prodi: "Mohon Pilih Program Studi",
          auditor_kode: "Mohon Pilih Ketua Auditor",
          periode: "Mohon Masukkan Periode",
          opening_ami: "Mohon Pilih Jadwal Opening AMI",
          pengisian_dokumen: "Mohon Pilih Jadwal Pengisian Dokumen",
          deskevaluasion: "Mohon Pilih Jadwal Desk Evaluasion",
          assessment: "Mohon Pilih Jadwal Assessment",
          tindakan_koreksi: "Mohon Pilih Jadwal Tindakan Koreksi",
          laporan_ami: "Mohon Pilih Jadwal Penyusunan Laporan AMI",
          rtm: "Mohon Pilih Jadwal RTM",
      },
      errorPlacement: function(error, element) {
          error.addClass("invalid-feedback");
  
          if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
          }
          else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
              error.insertAfter(element.parent().parent());
          }
          else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
              error.appendTo(element.parent().parent());
          }
          else {
              error.insertAfter(element);
          }
      },
      highlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-invalid").removeClass("is-valid");
          }
      },
      unhighlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-valid").removeClass("is-invalid");
          }
      }
    });
    // validate signup form on keyup and submit
    $("#PenjadwalanAMIFormEdit").validate({
      rules: {
          prodi: {
              required: true,
          },
          periode: {
              required: true,
          },
          opening_ami: {
              required: true,
          },
          pengisian_dokumen: {
              required: true,
          },
          deskevaluasion: {
              required: true,
          },
          assessment: {
              required: true,
          },
          tindakan_koreksi: {
              required: true,
          },
          laporan_ami: {
              required: true,
          },
          rtm: {
              required: true,
          },
      },
      messages: {
          prodi: "Mohon Pilih Program Studi",
          auditor_kode: "Mohon Pilih Ketua Auditor",
          periode: "Mohon Masukkan Periode",
          opening_ami: "Mohon Pilih Jadwal Opening AMI",
          pengisian_dokumen: "Mohon Pilih Jadwal Pengisian Dokumen",
          deskevaluasion: "Mohon Pilih Jadwal Desk Evaluasion",
          assessment: "Mohon Pilih Jadwal Assessment",
          tindakan_koreksi: "Mohon Pilih Jadwal Tindakan Koreksi",
          laporan_ami: "Mohon Pilih Jadwal Penyusunan Laporan AMI",
          rtm: "Mohon Pilih Jadwal RTM",
      },
      errorPlacement: function(error, element) {
          error.addClass("invalid-feedback");
  
          if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
          }
          else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
              error.insertAfter(element.parent().parent());
          }
          else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
              error.appendTo(element.parent().parent());
          }
          else {
              error.insertAfter(element);
          }
      },
      highlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-invalid").removeClass("is-valid");
          }
      },
      unhighlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-valid").removeClass("is-invalid");
          }
      }
    });
    // validate signup form on keyup and submit
    $("#DokumenSpmiAmiForm").validate({
      rules: {
          kategori_dokumen: {
              required: true,
          },
          nama_dokumen: {
              required: true,
          },
          file_spmi_ami: {
              required: true,
          },
      },
      messages: {
          kategori_dokumen: "Mohon Pilih Kategori Dokumen",
          nama_dokumen: "Mohon Masukkan Nama Dokumen",
          file_spmi_ami: "Mohon Upload Dokumen",
      },
      errorPlacement: function(error, element) {
          error.addClass("invalid-feedback");
  
          if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
          }
          else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
              error.insertAfter(element.parent().parent());
          }
          else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
              error.appendTo(element.parent().parent());
          }
          else {
              error.insertAfter(element);
          }
      },
      highlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-invalid").removeClass("is-valid");
          }
      },
      unhighlight: function(element, errorClass) {
          if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
              $(element).addClass("is-valid").removeClass("is-invalid");
          }
      }
    });
    // validate signup form on keyup and submit
    $("#KelolaTargetForm").validate({
        rules: {
            dokumen_nama: {
                required: true,
            },
            pertanyaan_nama: {
                required: true,
            },
            dokumen_tipe: {
                required: true,
            },
        },
        messages: {
            dokumen_nama: "Mohon Masukkan Nama Dokumen",
            pertanyaan_nama: "Mohon Masukkan Pertanyaan",
            dokumen_tipe: "Mohon Masukkan Tipe Dokumen",
        },
        errorPlacement: function(error, element) {
            error.addClass("invalid-feedback");
    
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            }
            else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                error.insertAfter(element.parent().parent());
            }
            else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                error.appendTo(element.parent().parent());
            }
            else {
                error.insertAfter(element);
            }
        },
        highlight: function(element, errorClass) {
            if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
                $(element).addClass("is-invalid").removeClass("is-valid");
            }
        },
        unhighlight: function(element, errorClass) {
            if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
                $(element).addClass("is-valid").removeClass("is-invalid");
            }
        }
      });
  
  });
});