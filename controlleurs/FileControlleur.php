<?php

    class FileControlleur extends controlleur {

        public function __construct()
        {
            // sleep(10);
        }

        public function uploadFile($name='adm_file_upload') {
            $this->proceedFiles($_FILES[$name]);
        }

        public function proceedFiles($files)
        {
            $error = false;
            $filesname = [];
            $filelist = [];

            for ($i=0; $i<count($files['name']); $i++) {
                $filename = uniqid();
                $fileext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $filewholename = $filename. '.' .$fileext;

                if (!in_array(strtolower($fileext), ['jpg', 'png', 'gif', 'jpeg'])) {
                    $error = "The file ". $files['name'][$i] ." type is not allowed !";
                    break;
                }
                else if ($files['error'][$i]>0) {
                    $error = "The file ". $files['name'][$i] ." may contains error(s) !";
                    break;
                }
                else if (!move_uploaded_file($files['tmp_name'][$i], Config::$fields_files_path . $filewholename)) {
                    $error = "The file ". $files['name'][$i] ." could not be moved ! Maybe permission denied or MAx FILE SIZE passed.";
                    break;
                }
                else {
                    $filelist[] = ["origin" => $files['name'][$i], "savename" => $filewholename, "idfile" => $filename];
                }
            }

            if ($error) {
                $this->json_error($error, ["newtoken" => Posts::getCSRFTokenValue()]);
                exit();
            }
            else {
                $this->json_success("Uploaded !", [
                    "newtoken" => Posts::getCSRFTokenValue(),
                    "names" => implode('|', $filesname),
                    "saved" => $filelist
                ]);
                exit();
            }
        }

    }