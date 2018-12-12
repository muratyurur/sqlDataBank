<?php

class Reports extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        /** Setting viewFolder */
        $this->viewFolder = "reports_v";

        if (!get_active_user())
            redirect(base_url("login"));

        /** Loading Models */
        $this->load->model("report_model");
        $this->load->model("file_model");
    }

    public function index()
    {
        $viewData = new stdClass();

        /** Taking all data from the table */
        $items = $this->report_model->get_all(
            array()
        );

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "list";
        $viewData->items = $items;

        /** Loading View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function new_form()
    {
        $viewData = new stdClass();

        $this->load->model("department_model");

        $viewData->departments = $this->department_model->get_all(
            array(
                "isActive" => 1
            ), "title ASC"
        );

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "add";

        /** Loading View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function save()
    {
        $user = get_active_user();

        /** Loading Form Validation Library */
        $this->load->library("form_validation");

        /** Setting validation rules */
        $this->form_validation->set_rules("title", "Rapor Adı", "trim|required");

        /** Translating validation error messages */
        $this->form_validation->set_message(
            array(
                "required" => "<b>{field}</b> alanı boş bırakılamaz..."
            )
        );

        /** Running Form Validation */
        $validate = $this->form_validation->run();

        /** If validation is successful */
        if ($validate) {

            /** Then start insert statement */
            $insert = $this->report_model->add(
                array(
                    "title" => $this->input->post("title"),
                    "department_id" => $this->input->post("department_id"),
                    "url" => convertToSEO($this->input->post("title")),
                    "isActive" => 1,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "createdBy" => $user->id
                )
            );

            /** If insert statement is successful */
            if ($insert) {

                /** Set the notification as Success */
                $alert = array(
                    "type" => "success",
                    "title" => "İşlem Başarılı",
                    "text" => "Kayıt başarılı bir şekilde eklendi.."
                );

                /** If insert statement is unsuccessful */
            } else {

                /** Set the notification as Error */
                $alert = array(
                    "type" => "error",
                    "title" => "İşlem Başarısız",
                    "text" => "Kayıt işlemi esnasında bir sorun oluştu.."
                );

            }

            /** Set the session data with result */
            $this->session->set_flashdata("alert", $alert);

            /** Redirect to Module's List Page */
            redirect(base_url("reports"));

            /** If validation is unsuccessful */
        } else {
            /** Then reloading view and show error messages below the inputs */
            $viewData = new stdClass();

            /** Defining data to be sent to view */
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = "add";
            $viewData->form_error = true;

            /** Reloading view */
            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }

    }

    public function update_form($id)
    {
        $viewData = new stdClass();

        $this->load->model("department_model");

        $viewData->departments = $this->department_model->get_all(
            array(
                "isActive" => 1
            ), "title ASC"
        );

        /** Taking the specific row's data from the table */
        $item = $this->report_model->get(
            array(
                "id" => $id
            )
        );

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "update";
        $viewData->item = $item;

        /** Loading View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function update($id, $report_type = "image", $oldFolderName = "")
    {
        /** Loading Form Validation Library */
        $this->load->library("form_validation");

        /** Setting validation rules */
        $this->form_validation->set_rules("title", "Rapor Adı", "trim|required");

        /** Translating validation error messages */
        $this->form_validation->set_message(
            array(
                "required" => "<b>{field}</b> alanı boş bırakılamaz..."
            )
        );

        /** Running Form Validation */
        $validate = $this->form_validation->run();

        /** If validation is successful */
        if ($validate) {

            /** Then start update statement */
            $update = $this->report_model->update(
                array(
                    "id" => $id
                ),
                array(
                    "title" => $this->input->post("title"),
                    "department_id" => $this->input->post("department_id"),
                    "url" => convertToSEO($this->input->post("title")),
                )
            );

            /** If update statement is successful */
            if ($update) {

                /** Set the notification as Success */
                $alert = array(
                    "type" => "success",
                    "title" => "İşlem Başarılı",
                    "text" => "Kayıt başarılı bir şekilde güncellendi.."
                );

                /** If update statement is Unsuccessful */
            } else {

                /** Set the notification as Error */
                $alert = array(
                    "type" => "error",
                    "title" => "İşlem Başarısız",
                    "text" => "Kayıt güncelleme işlemi esnasında bir sorun oluştu.."
                );

            }

            $this->session->set_flashdata("alert", $alert);

            /** Redirect to Module's List Page */
            redirect(base_url("reports"));

            /** If validation is unsuccessful */
        } else {

            /** Then reload view and show error messages below the inputs */
            $viewData = new stdClass();

            /** Taking the specific row's data from the table */
            $item = $this->report_model->get(
                array(
                    "id" => $id
                )
            );

            /** Defining data to be sent to view */
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = "update";
            $viewData->item = $item;
            $viewData->form_error = true;

            /** Reloading View */
            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }

    }

    public function delete($id)
    {
        $report = $this->report_model->get(
            array(
                "id" => $id
            )
        );

        if ($report) {

            /** Starting delete statement */
            $delete = $this->report_model->delete(
                array(
                    "id" => $id
                )
            );

            /** If Delete Statement is successful */
            if ($delete) {

                /** Set the notification as Success */
                $alert = array(
                    "type" => "success",
                    "title" => "İşlem Başarılı",
                    "text" => "Kayıt başarılı bir şekilde silindi.."
                );

                /** If delete statement is unsuccessful */
            } else {

                /** Set the notification as Error */
                $alert = array(
                    "type" => "error",
                    "title" => "İşlem Başarısız",
                    "text" => "Kayıt silme işlemi esnasında bir sorun oluştu.."
                );
            }

            $this->session->set_flashdata("alert", $alert);

            /** Redirect to Module's List Page */
            redirect(base_url("reports"));
        }
    }

    public function isActiveSetter($id)
    {
        /** If the posted data is true then set the isActive variable's value 1 else set 0 */
        $isActive = ($this->input->post("data") === "true") ? 1 : 0;

        /** Update the isActive column with isActive varible's value */
        $this->report_model->update(
            array(
                "id" => $id
            ),
            array(
                "isActive" => $isActive
            )
        );
    }

    public function upload_form($id)
    {
        $viewData = new stdClass();

        /** Taking the specific row's data from reportss table */
        $item = $this->report_model->get(
            array(
                "id" => $id
            )
        );

        $viewData->item = $item;

        /** Taking all files of a specific parent from the child table */
        $items = $this->file_model->get_all(
            array(
                "report_id" => $id
            )
        );

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "image";
        $viewData->items = $items;

        /** Loading View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function file_upload($report_id)
    {
        $user = get_active_user();

        /** Taking the name of uploaded file */
        $file_name = convertToSEO(pathinfo($_FILES["file"]["name"], PATHINFO_FILENAME)) . "." . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $file_type = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

        /** CodeIgniter 'Upload Library's configuration set */
        $config["allowed_types"] = "*";
        $config["upload_path"] = "uploads/{$this->viewFolder}";
        $config["file_name"] = $file_name;

        /** Load CodeIgniter 'Upload Library' */
        $this->load->library("upload", $config);

        /** Doing upload by 'do_upload' method */
        $upload = $this->upload->do_upload("file");

        /** If Upload Process is successful */
        if ($upload) {
            /** Create a Variable and set with Uploaded File's name */
            $uploaded_file = $this->upload->data("file_name");

            /** Insert reference records to child table for uploaded images */
            $this->file_model->add(
                array(
                    "url" => "{$config["upload_path"]}/$uploaded_file",
                    "file_type" => $file_type,
                    "isActive" => 1,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "createdBy" => $user->id,
                    "report_id" => $report_id
                )
            );

            /** If Upload Process is Unsuccesful */
        } else {

            /** Set the notification as Error */
            $alert = array(
                "type" => "error",
                "title" => "İşlem Başarısız",
                "text" => "Dosya yükleme işlemi esnasında bir sorun oluştu.."
            );

            $this->session->set_flashdata("alert", $alert);

            /** Redirect to Module's List Page */
            redirect(base_url("reports/upload_form/$report_id"));

            die();
        }
    }

    public function refresh_file_list($report_id)
    {
        $viewData = new stdClass();

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "image";
        /** Taking all images of a specific reports from the images table */
        $viewData->items = $this->file_model->get_all(
            array(
                "report_id" => $report_id
            )
        );

        /** Reload Render Element View */
        $render_html = $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/render_elements/file_list_v", $viewData, true);

        echo $render_html;
    }

    public function fileDelete($id, $report_id)
    {
        /** Taking the specific row's data from reportss table */
        $fileName = $this->file_model->get(
            array(
                "id" => $id
            )
        );

        /** Starting Delete Statement */
        $delete = $this->file_model->delete(
            array(
                "id" => $id
            )
        );

        /** If Image Delete Statement is successful */
        if ($delete) {

            /** Then deleting the file physically from disk */
            unlink($fileName->url);

            /** Set the notification as Success */
            $alert = array(
                "type" => "success",
                "title" => "İşlem Başarılı",
                "text" => "Dosya başarılı bir şekilde silindi.."
            );

            /** If Image Delete Statement is Unsuccessful */
        } else {

            /** Set the notification as Error */
            $alert = array(
                "type" => "error",
                "title" => "İşlem Başarısız",
                "text" => "Dosya silme işlemi esnasında bir sorun oluştu.."
            );

        }

        $this->session->set_flashdata("alert", $alert);

        /** Redirect to Module's List Page */
        redirect(base_url("reports/upload_form/$report_id"));
    }

    public function fileDeleteAll($report_id)
    {
        /** Taking the specific row's data from reportss table */
        $files = $this->file_model->get_all(
            array(
                "report_id" => $report_id
            )
        );

        /** Starting Delete Statement */
        $deleteAll = $this->file_model->delete(
            array(
                "report_id" => $report_id
            )
        );

        /** If Image Delete Statement is successful */
        if ($deleteAll) {

            /** Deleting files physically from disk */
            foreach ($files as $file) {
                unlink($file->url);
            }

            /** Set the notification as Success */
            $alert = array(
                "type" => "success",
                "title" => "İşlem Başarılı",
                "text" => "Tüm dosyalar başarılı bir şekilde silindi.."
            );

            /** If Image Delete Statement is Unsuccessful */
        } else {

            /** Set the notification as Error */
            $alert = array(
                "type" => "error",
                "title" => "İşlem Başarısız",
                "text" => "Dosya silme işlemi esnasında bir sorun oluştu.."
            );
        }

        $this->session->set_flashdata("alert", $alert);

        /** Redirect to Module's List Page */
        redirect(base_url("reports/upload_form/$report_id"));
    }

    public function fileIsActiveSetter($id)
    {
        /** If the posted data is true then set the isActive variable's value 1 else set 0 */
        $isActive = ($this->input->post("data") === "true") ? 1 : 0;

        /** Update the isActive column with isActive varible's value */
        $this->file_model->update(
            array(
                "id" => $id,
            ),
            array(
                "isActive" => $isActive
            )
        );
    }
}