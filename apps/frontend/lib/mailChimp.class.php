<?php
/**
 * Basic class to wrap a MailChimp form. Requires the 'Curler' PHP class.
 *
 * @author     Corey McMahon
 */
  class MailChimp {

      private $endpoint, $u, $id, $fNameField, $lNameField, $emailField;


      /**
       * Instantiate the MailChimp class with a bunch of constants
       *
       * @param String $endpoint The URL that the MailChimp form ordinarily POSTs to
       * @param String $u The hidden 'u' parameter that identifies a user / form
       * @param String $id The hidden 'id' parameter that identifies a user / form
       * @param String $fNameField The name of the merge field that will include the first name (usually MERGEx, where x is an integer)
       * @param String $lNameField The name of the merge field that will include the last name (usually MERGEx, where x is an integer)
       * @param String $emailField The name of the merge field that will include the email address (usually MERGEx, where x is an integer)
       */
      public function MailChimp($endpoint, $u, $id, $fNameField, $lNameField, $emailField) {
          $this->endpoint = $endpoint;
          $this->u = $u; 
          $this->id = $id;
          $this->fNameField = $fNameField; 
          $this->lNameField = $lNameField; 
          $this->emailField = $emailField; 
      }

      /**
       * Register the given email address to the MailChimp form
       *
       * @param String $fname First name of the user to register
       * @param String $lname Last name of the user to register
       * @param String $email Email address of the user to register
       */
      public function register($fname,$lname,$email) {
        $fields = array(
            $this->fNameField => urlencode($fname),
            $this->lNameField => urlencode($lname),
            $this->emailField => urlencode($email),
            'u' => $this->u,
            'id' => $this->id
        );

        // POST the request to the MailChimp form endpoint
        curler::curlPost($this->endpoint, $fields , false);
      }
  }

?>
