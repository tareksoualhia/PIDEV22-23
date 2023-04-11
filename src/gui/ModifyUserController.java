/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gui;

import entite.User;
import java.io.IOException;
import java.net.URL;
import java.text.ParseException;
import java.util.ResourceBundle;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.TextField;
import service.UserCRUD;

/**
 * FXML Controller class
 *
 * @author aminh
 */
public class ModifyUserController implements Initializable {

    @FXML
    private TextField idE;
    @FXML
    private TextField emailUser;
    @FXML
    private TextField NomUser;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        // TODO
    }    

    @FXML
    private void getUser(ActionEvent event) throws IOException , ParseException{
             String sid = idE.getText();
        int id = Integer.parseInt(sid);
        User e = UserCRUD.findUserById(id);
        emailUser.setText(e.getEmail());
           NomUser.setText(e.getNom());  
    }

    @FXML
    private void deleteUser(ActionEvent event) throws IOException {
              String sid = idE.getText();
         int id = Integer.parseInt(sid);
         UserCRUD.deleteUser(id);
    }

    @FXML
    private void updateUser(ActionEvent event) {
        
        
              String sid = idE.getText();
             int id = Integer.parseInt(sid);
              String txtemail = emailUser.getText();
             String txtnom = NomUser.getText();
               User e = new User();
               e.setId(id);
               e.setEmail(txtemail);
                e.setNom(txtnom);


                 UserCRUD.modifierUser(e);
    }
    
}
