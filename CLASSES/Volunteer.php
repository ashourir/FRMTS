<?php

/**
 * Description of Volunteer
 *
 * @author cormi
 */
class Volunteer
{
  private int $volunteerId;
  private string $password;
  private string $dob;
  private string $email;
  private int $activeDocId;

  //methods go here:

  //JEREMY
  //generate an alphanumeric token and store in db associated with email
  //used to verify email address of registering volunteer
  public static function GenerateToken(): string
  {
    return hash("sha256", time());
  }


  //JEREMY
  //returns true if email DOES NOT exist in database
  public static function VerifyAvailableEmail(string $email): bool
  {
    global $con;
    $stmt = $con->prepare("CALL VerifyVolunteerEmail(?)");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    return $stmt->get_result()->num_rows == 0;
  }

  //JEREMY
  //validates and email and pass against the DB
  //returns a volunteer obj if credentials are valid
  //returns invalid email or pass depending on which causes the invalid state
  public static function ValidateCredentials(string $email, string $pass)
  {
    global $con;
    $stmt = $con->prepare("CALL GetCredentials(?)");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      return "invalid email";
    } else {
      list($id, $hash) = $result->fetch_row();
      if (password_verify($pass, $hash)) {
        $volunteer = new Volunteer();
        $volunteer->volunteerId = (int)$id;
        $volunteer->email = $email;
        return $volunteer;
      } else {
        return "invalid password";
      }
    }
  }


  /**
   * This function will query the database Volunteer table to retrieve a
   * volunteer record that matches the $id.
   * 
   * @param int $id id unique identifier
   * @author Rodrigo Castro
   * @return Volunteer Volunteer object data set
   */
  public static function GetVolunteerById(int $id)
  {
    global $con;
    $stmt = $con->prepare("CALL GetVolunteerById(?)");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

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

  //JEREMY
  //returns the token associated with the email during sign up
  //used to validate that the users supplied email 
  public static function GetToken(string $email): string
  {
    global $con;
    $stmt = $con->prepare("Call GetToken(?)");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0];
  }



  //JEREMY
  //Uses token sent in confirmation email to confirm volunteer
  //return true if email exists
  public static function VerifyToken(string $token)
  {
    global $con;
    $tk = sanitizeSQL($token);
    $stmt = $con->prepare("CALL VerifyToken(?)");
    $stmt->bind_param('s', $tk);
    $stmt->execute();
    return $stmt->get_result()->num_rows == 1;
  }

  //Jeffery
  //Search for any user emails matching the specified string.  returns an array of matching emails or an empty array if no emails are found
  //Currently not in use, replaced by GetAllVolunteerEmails()
  public static function SearchVolunteerEmails(string $search): array
  {
    global $con;
    $emailArray = array();
    $search = "%" . $search . "%";
    $stmt = $con->prepare("CALL SearchVolunteerEmails(?)");
    $stmt->bind_param('s', $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
      foreach ($rows as $row) {
        $email = $row["email"];
        array_push($emailArray, $email);
      }
    }
    return $emailArray;
  }

  public static function GetAllVolunteerEmails(): array
  {
    global $con;
    $emailArray = array();
    $stmt = $con->prepare("CALL GetAllVolunteerEmails()");
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
      foreach ($rows as $row) {
        $email = $row["email"];
        array_push($emailArray, $email);
      }
    }
    return $emailArray;
  }

  //Jeffery
  //Change a user to inactive, functionally a soft-delete from the database.
  public static function SetVolunteerInactive(int $id): bool
  {
    global $con;
    $stmt = $con->prepare("CALL SetVolunteerInactive(?)");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }

  //Jeffery
  //Searches the db for a exact-match email & returns the id# of that user
  public static function GetUserIdByEmail(string $email): int
  {
    global $con;
    $stmt = $con->prepare("CALL GetUserIdByEmail(?)");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
      return $result->fetch_row()[0];
    } else {
      return 0;
    }
  }

  //Jeremy 
  //returns email of a volunteer based on unique token
  public static function GetVolunteerEmail(string $token): string
  {
    global $con;
    $stmt = $con->prepare("CALL GetVolunteerEmail(?)");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0];
  }

  //JEREMY
  //initial input of email, DOB and a token used to verify email address
  //token will be deleted and password will be set in FinalizeVolunteer method
  public static function AddVolunteer(string $email, string $dob): bool
  {
    $token = self::GenerateToken();

    global $con;
    $stmt = $con->prepare("CALL CreateVolunteer(?,?,?)");
    $stmt->bind_param(
      'sss',
      $email,
      $dob,
      $token
    );
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }


  //JEREMY
  //uses the token sent in verification email locate the correct account and update the password.
  public static function UpdateVolunteer(string $password, string $token): bool
  {
    $tk = sanitizeSQL($token);
    $email = self::GetVolunteerEmail($tk);
    if ($email) {
      $hash = password_hash($password, PASSWORD_BCRYPT);
      global $con;
      $stmt = $con->prepare("CALL UpdateVolunteer(?,?)");
      $stmt->bind_param('ss', $hash, $email);
      $stmt->execute();
      return $stmt->affected_rows == 1;
    } else {
      return false;
    }
  }


  //JEREMY
  public static function ResetPassword(string $email, string $dob): mixed
  {
    $token = self::GenerateToken(45);

    global $con;
    $stmt = $con->prepare("CALL ResetPassword(?, ?, ?)");
    $stmt->bind_param(
      'sss',
      $email,
      $dob,
      $token
    );
    $stmt->execute();
    if ($stmt->affected_rows == 1) {
      return $token;
    } else {
      return false;
    }
  }



  //JEREMY
  public static function UpdatePassword(int $id, string $passwd): bool
  {
    global $con;
    $hash = password_hash($passwd, PASSWORD_BCRYPT);
    $stmt = $con->prepare("CALL UpdatePassword(?,?)");
    $stmt->bind_param('is', $id, $hash);
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }

  public static function GetAllActiveVolunteerEmails(){
    global $con;
    $emailArray = array();
    $stmt = $con->prepare("CALL GetAllActiveVolunteerEmails()");
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
        foreach ($rows as $row) {
            $email = $row["email"];
            array_push($emailArray, $email);
        }
    }
    return $emailArray;
}


  //JEFFERY
  //calls the function to get all active Volunteer emails and returns a formatted string for options in a select box to employee.php
  public static function GetAllVolunteersFormatted()
  {
    $emails = Volunteer::GetAllActiveVolunteerEmails();
    $volunteerEmails = "";
    foreach ($emails as $email) {
      $volunteerEmails .= "<option value='$email'>$email</option>";
    }
    return "$volunteerEmails";
  }

  //  JEREMY
  public function GetHistory()
  {
    global $con;
    $stmt = $con->prepare('CALL GetVolunteerHistory(?)');
    $stmt->bind_param('i', $this->volunteerId);
    $stmt->execute();
    $response = "<div class='volunteer_history'>";
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $response .= "<table><tr><th>Document</th><th>Work Started</th><th>Work Completed</th><th>Role</th></tr>";
      while (list($startDate, $endDate, $doc_status, $doc_name, $doc_id) = $result->fetch_row()) {
        if ($endDate) { //skips displaying what the volunteer is currently working on
          $response .= <<<_HISTORY
          <tr id="history_$doc_id">
            <td>$doc_name</td>
            <td>$startDate</td>
            <td>$endDate</td>
            <td>$doc_status</td>
          </tr>
        _HISTORY;
        }
      } //end while
      $response .= "</table>";
    } else {
      $response .= "No work history";
    }
    $response .= "</div>"; //close volunteer_history div
    $stmt->close();
    return $response;
  }

  //JEREMY
  public static function GetActiveDocumentId(Volunteer $volunteer)
  {
    global $con;
    $stmt = $con->prepare('CALL GetActiveDocumentId(?)');
    $stmt->bind_param('i', $volunteer->volunteerId);
    $stmt->execute();
    list($docId, $historyId) = $stmt->get_result()->fetch_row();
    $volunteer->activeDocId = $docId ?? -1;
    $stmt->close();
    return $historyId;
  }



  //JEREMY
  public static function StartNewProject(int $volunteerId, int $documentId, int $statusId)
  {
    global $con;
    $stmt = $con->prepare('CALL StartVolunteerTask(?,?,?, @result)');
    $stmt->bind_param('iii', $volunteerId, $documentId, $statusId);
    $stmt->execute();
    $result = $con->query('SELECT @result as RES');
    $status = $result->fetch_row()[0];
    $stmt->close();
    return $status;
  }

  //JEREMY
  public static function IsFirstJobType(int $volunteerId, int $statusId)
  {
    global $con;
    $stmt = $con->prepare('CALL GetCountHistoryJobType(?,?)');
    $stmt->bind_param('ii', $volunteerId, $statusId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    return $result;
  }

  //getters, setters, constructor
  public function __set($prop, $value)
  {
    $this->$prop = $value;
  }

  public function __get($prop)
  {
    return $this->$prop;
  }

  public function __construct()
  {
  }
}
