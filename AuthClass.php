<?php 
	class Auth {
		private $db; //menyimpan koneski database
		private $error; //menyimpan  error message

		###constructor untuk auth membutuhkan satu parameter yaitu koneksi ke database

		function __construct($db_conn) {
			$this->db = $db_conn;
			session_start();
		}


		public function register($nama, $email, $password) {
			try {
				$hashPasswd = password_hash($password, PASSWORD_DEFAULT);
				$stmt = $this->db->prepare("INSERT INTO tb_auth(nama, email, password) VALUES (:nama, :email, :pass)");
				$stmt->bindParam(":nama", $nama);
				$stmt->bindParam(":email", $email);
				$stmt->bindParam(":pass", $hashPasswd);
				$stmt->execute();
				return true;
			} catch (PDOException $e) {
				if($e->errorInfo[0] == 23000) {
					$this->error = "email sudah digunakan";
					return false;
				} else {
					$e->getMessage();
					return false;
				}
			}
		}


		public function login($email, $password) {
			try {
				$stmt = $this->db->prepare("SELECT * FROM tb_auth WHERE email= :email");
				$stmt->bindParam(":email", $email);
				$stmt->execute();
				$data = $stmt->fetch();

				if($stmt->rowCount() > 0) {
					if(password_verify($password, $data['password'])) {
						$_SESSION['user_session'] = $data['id'];
						return true;
					} else {
						$this->error = "email atau password salah";
						return false;
					}
				} else {
						$this->error = "email atau password salah";
						return false;
					}
			} catch(PDOException $e) {
				$e->getMessage();
				return false;
			}	
		}


		### Start : fungsi cek login user ###  
		public function isLoggedIn() {
			if(isset($_SESSION['user_session'])) {
				return true;
			}
		}


		//fungsi ambil data user yang sudah login
		public function getUser() {
			// Cek apakah sudah login 

	        if (!$this->isLoggedIn()) {

	            return false;
	        }

	        try {

	            // Ambil data user dari database 

	            $stmt = $this->db->prepare("SELECT * FROM tb_auth WHERE id = :id");

	            $stmt->bindParam(":id", $_SESSION['user_session']);

	            $stmt->execute();

	            return $stmt->fetch();
	        } catch (PDOException $e) {

	            echo $e->getMessage();

	            return false;
	        }
		}


		public function logout() {
			session_destroy();
			unset($_SESSION['user_session']);
			return true;
		}

		public function getLastError() {
			return $this->error;
		}
	}
?>