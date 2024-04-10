<?php

/**
 * Description of Employee
 *
 * @author cormi
 */
class Employee
{
  private $empId;
  private $username;
  private $password;
  private $isActive;
  private $activeDocId;


  //JEREMY
  //returns true if username DOES EXIST in database
  public static function VerifyEmployeeUsername(string $username): bool
  {
    global $con;
    $stmt = $con->prepare("CALL VerifyUniqueEmployee(?)");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    return $stmt->get_result()->num_rows == 1;
  }

  //JEREMy
  //validates and email and pass agaisnt the DB
  //returns an employee obj if credentials are valid
  //returns invalid email or pass depending on which causes the invalid state
  public static function ValidateCredentials(string $username, string $pass)
  {
    global $con;
    $stmt = $con->prepare("CALL ValidateEmployeeCredentials(?)");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      return "invalid email";
    } else {
      list($id, $hash, $actDocId) = $result->fetch_row();
      if (password_verify($pass, $hash)) {
        $employee = new Employee();
        $employee->empId = (int)$id;
        $employee->username = $username;
        $employee->activeDocId = (int)$actDocId;
        return $employee;
      } else {
        return "invalid password";
      }
    }
  }

  //JEFFERY
  //checks if an Approver account has an active approver document in progress, returns a boolean
  public static function CheckActiveApproverDocument(int $empId): int
  {
    global $con;
    $stmt = $con->prepare("CALL CheckActiveApproverDocument(?)");
    $stmt->bind_param('i', $empId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row["activeDocId"] | 0;
  }

  //JEFFERY
  //Once an employee selects a document to approve, the database updates 2 tables to show which employee is working on which document.
  //returns true if successful or false if an error occurs.
  public static function UpdateDocumentStatusAndEmployeeActiveDocId(int $empId, int $docId)
  {
    global $con;
    $stmt = $con->prepare("CALL UpdateDocumentStatusAndEmployeeActiveDocId(?,?, @result)");
    $stmt->bind_param('ii', $empId, $docId);
    $stmt->execute();
    $answer = $con->query("SELECT @result as RES");
    $result = $answer->fetch_row()[0];
    $stmt->close();
    echo $result;
  }

  /**
   * Set the employee's activeDocId to null and updates the document's status to 7 (complete)
   * @param int $empId unique identifier for employee table
   * @param int $docId unique identifier for document table
   * @return int 1 if success
   * @return int 0 if fail
   * @author Rodrigo Castro
   */
  public static function SetDocumentAsCompleteAndEmployeeActiveId(int $empId, int $docId)
  {
    global $con;
    $stmt = $con->prepare("CALL SetDocumentAsCompleteAndEmployeeActiveId(?,?, @result)");
    $stmt->bind_param('ii', $empId, $docId);
    $stmt->execute();
    $answer = $con->query("SELECT @result as RES");
    $result = $answer->fetch_row()[0];
    $stmt->close();
    echo $result;
  }

  /**
   * Set the employee's activeDocId to NULL and updates the document's status to 5 (complete)
   * @param int $empId unique identifier for employee table
   * @param int $docId unique identifier for document table
   * @return int 1 if success
   * @return int 0 if fail
   * @author Rodrigo Castro
   */
  public static function employeeDropDocument(int $empId, int $docId)
  {
    global $con;
    $stmt = $con->prepare("CALL employeeDropDocument(?,?, @result)");
    $stmt->bind_param('ii', $empId, $docId);
    $stmt->execute();
    $answer = $con->query("SELECT @result as RES");
    $result = $answer->fetch_row()[0];
    $stmt->close();
    echo $result;
  }

  /**
   * Set the employee's activeDocId to NULL and updates the document's status to 1 (transcribe)
   * @param int $empId unique identifier for employee table
   * @param int $docId unique identifier for document table
   * @return int 1 if success
   * @return int 0 if fail
   * @author Rodrigo Castro
   */
  public static function employeeRejectDocument(int $empId, int $docId)
  {
    global $con;
    $stmt = $con->prepare("CALL rejectDocumentApproval(?,?, @result)");
    $stmt->bind_param('ii', $empId, $docId);
    $stmt->execute();
    $answer = $con->query("SELECT @result as RES");
    $result = $answer->fetch_row()[0];
    $stmt->close();
    echo $result;
  }


  //JEFFERY
  //Checks the employee's current password to ensure it matches before resetting password.  Returns a string to the calling AJAX function to give the status.
  public static function CheckEmployeePassword(int $empId, string $userPass): string
  {
    global $con;
    $stmt = $con->prepare("CALL GetEmployeePassword(?)");
    $stmt->bind_param('i', $empId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      $currentPass = $row["password"];
      if (password_verify($userPass, $currentPass)) {
        return "The password you entered matches your current password";
      } else {
        return "The password you entered does not match your current password";
      }
    } else {
      return "Failed to find user";
    }
  }

  //JEFFERY
  //Updates an existing employee's password to the newly provided password and returns the number of affected rows (should always be 1 assuming it works)
  public static function UpdateEmployeePassword(int $empId, string $newPass): int
  {
    global $con;
    $stmt = $con->prepare("CALL UpdateEmployeePassword(?,?)");
    $stmt->bind_param("si", $newPass, $empId);
    $stmt->execute();
    return $stmt->affected_rows;
  }

  //JEFFERY
  //Adds a new employee to the db, returns either their db id# or 0 if the insert failed
  public static function AddNewEmployee(string $username, string $password): int
  {
    global $con;
    $stmt = $con->prepare("CALL AddNewEmployee(?, ?, @LID)");
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    if ($stmt->affected_rows == 1) {
      $result = $con->query('SELECT @LID AS last_id');
      $row = $result->fetch_assoc();
      return $row['last_id'];
    } else {
      return 0;
    }
  }

  //JEFFERY
  //Adds employee's selected roles, called immediately after a new employee has been added.  Returns a string to indicated success/failure
  public static function AddEmployeeRoles(int $employeeId, array $roles): string
  {
    global $con;
    $roleNum = 0;
    $counter = 0;
    foreach ($roles as $role) {
      switch ($role) {
        case "Admin":
          $roleNum = 4;
          break;
        case "Approver":
          $roleNum = 14;
          break;
        case "Uploader":
          $roleNum = 24;
          break;
        default:
          $roleNum = 0;
      }
      $stmt = $con->prepare("CALL AddEmployeeRole(?, ?)");
      $stmt->bind_param('is', $employeeId, $roleNum);
      $stmt->execute();
      if (mysqli_affected_rows($con) == 1) {
        $counter++;
        continue;
      } else {
        break;
      }
    }
    return (count($roles) == $counter) ? "New employee inserted" : "An error has occured";
  }
  //JUSTIN
  //Get employee roles by user id
  public static function GetEmpRoles(int $empid)
  {
    global $con;
    $stmt = $con->prepare("CALL GetEmployeeRoleByID(?)");
    $stmt->bind_param('s', $empid);
    $stmt->execute();
    $result = $stmt->get_result();
    //$out = mysqli_fetch_array($result);
    $out = array();
    while ($row = mysqli_fetch_array($result)) {
      $out[] = $row;
    }
    return $out;
  } //end GetEmpRoles

  //JEFFERY
  //Search for any staff usernames that match the specified string and return an array of usernames or an empty array if no usernames are found.
  //Currently not in use, from an earlier build; replaced by GetAllStaff()
  public static function SearchStaffUsernames(string $search): array
  {
    global $con;
    $usernameArray = array();
    $search = "%" . $search . "%";
    $stmt = $con->prepare("CALL SearchStaffUsernames(?)");
    $stmt->bind_param('s', $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
      foreach ($rows as $row) {
        $username = $row["username"];
        array_push($usernameArray, $username);
      }
    }
    return $usernameArray;
  }

  //JEFFERY
  //returns an array of employee usernames to the calling function to be formatted
  public static function GetAllEmployeeUsernames(): array
  {
    global $con;
    $usernameArray = array();
    $stmt = $con->prepare("CALL GetAllActiveEmployeeUsernames()");
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
      foreach ($rows as $row) {
        $username = $row["username"];
        array_push($usernameArray, $username);
      }
    }
    return $usernameArray;
  }
  //Alex
  public static function GetUnassignedEmployeeUsernames(): array
  {
    global $con;
    $usernameArray = array();
    $stmt = $con->prepare("CALL GetUnassignedEmployees()");
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
      foreach ($rows as $row) {
        $employee = ["employeeId" => $row["employeeId"], "username" => $row["username"]];
        array_push($usernameArray, $employee);
      }
    }
    return $usernameArray;
  }


  //JEFFERY
  //returns a formatted string of options for a select box on the employee.php page
  public static function GetAllEmployeesFormatted()
  {
    $usernames = Employee::GetAllEmployeeUsernames();
    $employeeUsernames = "";
    foreach ($usernames as $username) {
      $employeeUsernames .= "<option value='$username'>$username</option>";
    }
    return $employeeUsernames;
  }

  //Alex
  public static function GetUnassignedEmployeesFormatted(){
    $employees = Employee::GetUnassignedEmployeeUsernames();
    $employeeOptions = "";
    foreach ($employees as $employee) {
        $employeeOptions .= "<option value='{$employee["employeeId"]}'>{$employee["username"]}</option>";
    }
    return $employeeOptions;
}


  //Jeffery
  //Change a staff member to inactive, functionally a soft-delete from the database.
  public static function SetStaffInactive(int $id): bool
  {
    global $con;
    $stmt = $con->prepare("CALL SetStaffInactive(?)");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }

  //Jeffery
  //Searches the db for a exact-match email & returns the id# of that user
  public static function GetStaffIdByUsername(string $email): int
  {
    global $con;
    $stmt = $con->prepare("CALL GetStaffIdByUsername(?)");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
      return $result->fetch_row()[0];
    } else {
      return 0;
    }
  }

  /**
   * This function will query the database employee table to retrieve a
   * employee record that matches the $id and returns an employee object with all its information
   * 
   * @param int $id unique identifier
   * @author Rodrigo Castro
   * @return Employee Employee object data set
   */
  public static function GetEmployeeById(int $id)
  {
    global $con;
    $stmt = $con->prepare("CALL GetEmployeeById(?)");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    //change this later
    if ($result->num_rows == 0) {
      return "invalid id";
    } else {
      list($volunteerId, $dob, $activeDocId, $email) = $result->fetch_row();
      $volunteer = new Volunteer();
      $volunteer->volunteerId = $volunteerId;
      $volunteer->dob = $dob;
      $volunteer->activeDocId = (int)$activeDocId;
      $volunteer->email = $email;
      return $volunteer;
    }
  }

  /**
   * Gets the employee active document Id
   * @param Employee Employee Object
   * @author Rodrigo Castro
   * @return int with the documentId the employee is working at this moment
   */
  public static function GetActiveDocumentId(Employee $employee)
  {
    global $con;
    $empId = $employee->getEmpId();
    $stmt = $con->prepare('CALL GetEmployeeActiveDocumentId(?)');
    $stmt->bind_param('i', $empId);
    $stmt->execute();
    list($documentId, $historyApprovedId) = $stmt->get_result()->fetch_row();
    $employee->setActiveDocId($documentId ?? -1);
    $stmt->close();
    return $historyApprovedId;
  }

  //getters, setters, constructor
  public function getActiveDocId()
  {
    return $this->activeDocId;
  }
  public function getEmpId()
  {
    return $this->empId;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getIsActive()
  {
    return $this->isActive;
  }

  public function setEmpId($empId): void
  {
    $this->empId = $empId;
  }

  public function setUsername($username): void
  {
    $this->username = $username;
  }

  public function setPassword($password): void
  {
    $this->password = password_hash($password, PASSWORD_BCRYPT);
  }

  public function setIsActive($isActive): void
  {
    $this->isActive = $isActive;
  }
  public function setActiveDocId($activeDocId): void
  {
    $this->activeDocId = $activeDocId;
  }

  public function __construct()
  {
  }
}
