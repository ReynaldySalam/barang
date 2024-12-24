<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Supplier";
        $data['supplier'] = $this->admin->get('supplier');
        $this->template->load('templates/dashboard', 'supplier/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim|numeric');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Supplier";
            $this->template->load('templates/dashboard', 'supplier/add', $data);
        } else {
            $input = $this->input->post(null, true);
            if ($this->admin->check_phone_exists($input['no_telp'])) {
                set_pesan('Data supplier sudah ada.', false);
                redirect('supplier/add');
            } else {
            $input = $this->input->post(null, true);
            if ($this->admin->check_namasupplier_exists($input['nama_supplier'])) {
                set_pesan('Data supplier sudah ada.', false);
                redirect('supplier/add');
            } else {
            $save = $this->admin->insert('supplier', $input);
            if ($save) {
                set_pesan('data berhasil disimpan.');
                redirect('supplier');
            } else {
                set_pesan('data gagal disimpan', false);
                redirect('supplier/add');
            }
        }
        }
    }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();
    
        if ($this->form_validation->run() == false) {
            $data['title'] = "Supplier";
            $data['supplier'] = $this->admin->get('supplier', ['id_supplier' => $id]);
            $this->template->load('templates/dashboard', 'supplier/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $current_supplier = $this->admin->get('supplier', ['id_supplier' => $id]);
    
            if ($input['nama_supplier'] != $current_supplier['nama_supplier'] && $this->admin->check_namasupplier_exists($input['nama_supplier'])) {
                set_pesan('Data supplier sudah ada.', false);
                redirect('supplier/edit/' . $id);
            } else {
                if ($input['no_telp'] != $current_supplier['no_telp'] && $this->admin->check_phone_exists($input['no_telp'])) {
                    set_pesan('Data supplier sudah ada.', false);
                    redirect('supplier/edit/' . $id);
                } else {
                $update = $this->admin->update('supplier', 'id_supplier', $id, $input);
                if ($update) {
                    set_pesan('data berhasil diedit.');
                    redirect('supplier');
                } else {
                    set_pesan('data gagal diedit.');
                    redirect('supplier/edit/' . $id);
                }
            }
    
        }
    }
}

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('supplier', 'id_supplier', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('supplier');
    }
}
