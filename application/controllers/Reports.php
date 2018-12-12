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

            $path = "uploads/$this->viewFolder/";
            $folder_name = "";

            if ($report_type == "image") {

                $folder_name = convertToSEO($this->input->post("title"));
                $path = "$path/images";

            } else if ($report_type == "file") {

                $folder_name = convertToSEO($this->input->post("title"));
                $path = "$path/files";
            }

            if ($report_type != "video") {

                if (!rename("$path/$oldFolderName", "$path/$folder_name")) {

                    /** Set the notification as Error */
                    $alert = array(
                        "title" => "İşlem Başarısız",
                        "text" => "Rapor Üretilirken problem oluştur. (Yetki Hatası)",
                        "type" => "error"
                    );

                    /** Set the session data with result */
                    $this->session->set_flashdata("alert", $alert);

                    /** Redirect to Module's List Page */
                    redirect(base_url("reports"));
                    die();
                }
            }

            /** Then start update statement */
            $update = $this->report_model->update(
                array(
                    "id" => $id
                ),
                array(
                    "title" => $this->input->post("title"),
                    "folder_name" => $folder_name,
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

            if ($report->report_type == "image")

                $path = "uploads/$this->viewFolder/images/$report->folder_name";

            elseif ($report->report_type == "file")

                $path = "uploads/$this->viewFolder/files/$report->folder_name";

            $delete_folder = rrmdir($path);

            if (!$delete_folder) {

                /** Set the notification as Error */
                $alert = array(
                    "type" => "error",
                    "title" => "İşlem Başarısız",
                    "text" => "Kayıt silme işlemi esnasında bir sorun oluştu.."
                );

                $this->session->set_flashdata("alert", $alert);

                /** Redirect to Module's List Page */
                redirect(base_url("reports"));

                die();
            }

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

    public function rankSetter()
    {
        /** Set the values of $data array with posted data */
        $data = $this->input->post("data");

        /** Parsing values of $data array and put into the $order array */
        parse_str($data, $order);

        /** Set the values $items array with $order array and set keys as 'ord' and values as 'rank' */
        $items = $order["ord"];

        /** Update all  */
        foreach ($items as $rank => $id) {

            $this->report_model->update(
                array(
                    "id" => $id,
                    "rank!=" => $rank
                ),
                array(
                    "rank" => $rank
                )
            );
        }
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

        if ($item->report_type == "image") {
            /** Taking all images of a specific parent from the child table */
            $items = $this->image_model->get_all(
                array(
                    "report_id" => $id
                ), "rank ASC"
            );
        } elseif ($item->report_type == "file") {
            /** Taking all files of a specific parent from the child table */
            $items = $this->file_model->get_all(
                array(
                    "report_id" => $id
                ), "rank ASC"
            );
        } else {
            /** Taking all videos of a specific parent from the child table */
            $items = $this->video_model->get_all(
                array(
                    "report_id" => $id
                ), "rank ASC"
            );
        }

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "image";
        $viewData->items = $items;
        $viewData->report_type = $item->report_type;

        /** Loading View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function file_upload($report_id, $report_type, $folder_name)
    {
        /** Taking the name of uploaded file */
        $file_name = convertToSEO(pathinfo($_FILES["file"]["name"], PATHINFO_FILENAME)) . "." . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

        /** CodeIgniter 'Upload Library's configuration set */
        $config["allowed_types"] = ($report_type == "image") ? "jpg|jpeg|png" : "*";
        $config["upload_path"] = ($report_type == "image") ? "uploads/{$this->viewFolder}/images/$folder_name" : "uploads/{$this->viewFolder}/files/$folder_name";
        $config["file_name"] = $file_name;

        /** Load CodeIgniter 'Upload Library' */
        $this->load->library("upload", $config);

        /** Doing upload by 'do_upload' method */
        $upload = $this->upload->do_upload("file");

        /** If Upload Process is successful */
        if ($upload) {
            /** Create a Variable and set with Uploaded File's name */
            $uploaded_file = $this->upload->data("file_name");

            $modelName = ($report_type == "image") ? "image_model" : "file_model";

            /** Insert reference records to child table for uploaded images */
            $this->$modelName->add(
                array(
                    "url" => "{$config["upload_path"]}/$uploaded_file",
                    "rank" => 0,
                    "isActive" => 1,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "report_id" => $report_id
                )
            );

            /** If Upload Process is Unsuccesful */
        } else {
            /** Set alert with error message */
            echo "aktarım başarısız";
        }
    }

    public function refresh_file_list($report_id, $report_type)
    {
        $viewData = new stdClass();

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "image";

        $modelName = ($report_type == "image") ? "image_model" : "file_model";

        /** Taking all images of a specific reports from the images table */
        $viewData->items = $this->$modelName->get_all(
            array(
                "report_id" => $report_id
            )
        );

        $viewData->report_type = $report_type;

        /** Reload Render Element View */
        $render_html = $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/render_elements/file_list_v", $viewData, true);

        echo $render_html;
    }

    public function fileDelete($id, $parent_id, $report_type)
    {
        $modelName = ($report_type == "image") ? "image_model" : "file_model";

        /** Taking the specific row's data from reportss table */
        $fileName = $this->$modelName->get(
            array(
                "id" => $id
            )
        );

        /** Starting Delete Statement */
        $delete = $this->$modelName->delete(
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
                "text" => "Görsel başarılı bir şekilde silindi.."
            );

            /** If Image Delete Statement is Unsuccessful */
        } else {

            /** Set the notification as Error */
            $alert = array(
                "type" => "error",
                "title" => "İşlem Başarısız",
                "text" => "Görsel silme işlemi esnasında bir sorun oluştu.."
            );

        }

        $this->session->set_flashdata("alert", $alert);

        /** Redirect to Module's List Page */
        redirect(base_url("reports/upload_form/$parent_id"));
    }

    public function fileDeleteAll($parent_id, $report_type)
    {
        $modelName = ($report_type == "image") ? "image_model" : "file_model";

        /** Taking the specific row's data from reportss table */
        $files = $this->$modelName->get_all(
            array(
                "report_id" => $parent_id
            )
        );

        /** Starting Delete Statement */
        $deleteAll = $this->$modelName->delete(
            array(
                "report_id" => $parent_id
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
                "text" => "Tüm görseller başarılı bir şekilde silindi.."
            );

            /** If Image Delete Statement is Unsuccessful */
        } else {

            /** Set the notification as Error */
            $alert = array(
                "type" => "error",
                "title" => "İşlem Başarısız",
                "text" => "Görsel silme işlemi esnasında bir sorun oluştu.."
            );
        }

        $this->session->set_flashdata("alert", $alert);

        /** Redirect to Module's List Page */
        redirect(base_url("reports/upload_form/$parent_id"));
    }

    public function fileIsActiveSetter($id, $report_type)
    {
        if ($id && $report_type) {

            $modelName = ($report_type == "image") ? "image_model" : "file_model";

            /** If the posted data is true then set the isActive variable's value 1 else set 0 */
            $isActive = ($this->input->post("data") === "true") ? 1 : 0;

            /** Update the isActive column with isActive varible's value */
            $this->$modelName->update(
                array(
                    "id" => $id,
                ),
                array(
                    "isActive" => $isActive
                )
            );
        }
    }

    public function fileRankSetter($report_type)
    {
        /** Set the values of $data array with posted data */
        $data = $this->input->post("data");

        /** Parsing values of $data array and put into the $order array */
        parse_str($data, $order);

        /** Set the values $images array with $order array and set keys as 'ord' and values as 'rank' */
        $items = $order["ord"];

        $modelName = ($report_type == "image") ? "image_model" : "file_model";

        /** Update all records of the table for every index of $images array if there is a difference for rank column */
        foreach ($items as $rank => $id) {
            $this->$modelName->update(
                array(
                    "id" => $id,
                    "rank!=" => $rank
                ),
                array(
                    "rank" => $rank
                )
            );
        }
    }

    public function report_video_list($id)
    {
        $viewData = new stdClass();

        $report = $this->report_model->get(
            array(
                "id" => $id
            )
        );

        /** Taking all data from the table */
        $items = $this->video_model->get_all(
            array(
                "report_id" => $id
            ), "rank ASC"
        );

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "video/list";
        $viewData->items = $items;
        $viewData->report = $report;

        /** Loading View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function new_report_video_form($id)
    {
        $viewData = new stdClass();

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "video/add";
        $viewData->report_id = $id;

        /** Loading View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function report_video_save($id)
    {
        /** Loading Form Validation Library */
        $this->load->library("form_validation");

        /** Setting validation rules */
        $this->form_validation->set_rules("url", "Video URL", "trim|required");

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
            $insert = $this->video_model->add(
                array(
                    "url" => $this->input->post("url"),
                    "report_id" => $id,
                    "rank" => 0,
                    "isActive" => 1,
                    "createdAt" => date("Y-m-d H:i:s")
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
            redirect(base_url("reports/report_video_list/$id"));

            /** If validation is unsuccessful */
        } else {
            /** Then reloading view and show error messages below the inputs */
            $viewData = new stdClass();

            /** Defining data to be sent to view */
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = "video/add";
            $viewData->form_error = true;
            $viewData->report_id = $id;

            /** Reloading view */
            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }

    }

    public function update_report_video_form($id)
    {
        $viewData = new stdClass();

        /** Taking the specific row's data from the table */
        $item = $this->video_model->get(
            array(
                "id" => $id
            )
        );

        /** Defining data to be sent to view */
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "video/update";
        $viewData->item = $item;

        /** Loading View */
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function report_video_update($id, $report_id)
    {
        /** Loading Form Validation Library */
        $this->load->library("form_validation");

        /** Setting validation rules */
        $this->form_validation->set_rules("url", "Video URL", "trim|required");

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
            $update = $this->video_model->update(
                array(
                    "id" => $id
                ),
                array(
                    "url" => $this->input->post("url"),
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
            redirect(base_url("reports/report_video_list/$report_id"));

            /** If validation is unsuccessful */
        } else {

            /** Then reload view and show error messages below the inputs */
            $viewData = new stdClass();

            /** Taking the specific row's data from the table */
            $item = $this->video_model->get(
                array(
                    "id" => $id
                )
            );

            /** Defining data to be sent to view */
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = "video/update";
            $viewData->item = $item;
            $viewData->form_error = true;

            /** Reloading View */
            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }

    }

    public function reportVideoDelete($id, $report_id)
    {
        /** Starting delete statement */
        $delete = $this->video_model->delete(
            array(
                "id" => $id,
                "report_id" => $report_id
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
        redirect(base_url("reports/report_video_list/$report_id"));
    }

    public function reportVideoDeleteAll($report_id)
    {
        /** Starting delete statement */
        $delete = $this->video_model->delete(
            array(
                "report_id" => $report_id
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
        redirect(base_url("reports/report_video_list/$report_id"));
    }

    public function reportVideoIsActiveSetter($id)
    {
        /** If the posted data is true then set the isActive variable's value 1 else set 0 */
        $isActive = ($this->input->post("data") === "true") ? 1 : 0;

        /** Update the isActive column with isActive varible's value */
        $this->video_model->update(
            array(
                "id" => $id
            ),
            array(
                "isActive" => $isActive
            )
        );
    }

    public function rankGalleryVideoSetter()
    {
        /** Set the values of $data array with posted data */
        $data = $this->input->post("data");

        /** Parsing values of $data array and put into the $order array */
        parse_str($data, $order);

        /** Set the values $items array with $order array and set keys as 'ord' and values as 'rank' */
        $items = $order["ord"];

        /** Update all  */
        foreach ($items as $rank => $id) {

            $this->video_model->update(
                array(
                    "id" => $id,
                    "rank!=" => $rank
                ),
                array(
                    "rank" => $rank
                )
            );
        }
    }

}