/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gui;

import entite.Reponse;
import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Alert.AlertType;
import javafx.scene.control.Button;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.TextArea;
import javafx.stage.Stage;
import service.ReponseService;

/**
 * FXML Controller class
 *
 * @author DELL
 */
public class EditReponseController implements Initializable {
     @FXML
    private ChoiceBox<String> etat;        
    @FXML
    private TextArea reponse;
     @FXML
    private Button modifier;
        Reponse r;
Reponse rv=new Reponse();
     
     private String[] etatt ={"non traitee" , "traitee"};

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
         etat.getItems().addAll(etatt);
         etat.setOnAction(this::getEtat);
         
    }    
    
      public void getEtat(ActionEvent event){
        String T = etat.getValue();
        
    }
       public Boolean ValidateFields() {
        if (etat.getValue()==null | reponse.getText().isEmpty()  ) {
            Alert alert = new Alert(AlertType.WARNING);
            alert.setTitle("Validate fields");
            alert.setHeaderText(null);
            alert.setContentText("Please Enter Into The Fields");
            alert.showAndWait();
            return false;
        }

        return true;
     }
      
       @FXML
    private void save(ActionEvent event) throws IOException {
           if (rv == null) {

            System.out.println("choisir une reponse");
            Alert alert = new Alert(Alert.AlertType.WARNING);
            alert.setTitle("Modify Reclamation");
            alert.setHeaderText(null);
            alert.setContentText("your review is not modified !");

            alert.showAndWait();
        }else {
              if ( ValidateFields()==true) {
              rv.setEtat(etat.getValue());
             rv.setReponse(reponse.getText());
             
          
             ReponseService es = new ReponseService();
             try{
             es.update(rv);
             System.out.println("ok");}
             catch (Exception ex) {
            System.out.println(ex.getMessage());
        }
            System.out.println("Modification terminé");}
              
             
           
        
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle("Modification terminée avec succès.");
        alert.setHeaderText(null);
    alert.setContentText("Your review  has been modified.");
        alert.showAndWait();
        javafx.scene.Parent tableview = FXMLLoader.load(getClass().getResource("DashbordReponse.fxml"));
        Scene sceneview = new Scene(tableview);
        Stage window = (Stage)((Node)event.getSource()).getScene().getWindow();
        window.setScene(sceneview);
        window.show();
      
    }
    }
    
       void setDAta(int id, String t, String r) {
      
       rv.setId(id);
       etat.setValue(t);
       reponse.setText(r);
       
       
    
    
      
    }
     
    
}
