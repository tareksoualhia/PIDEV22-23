/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gui;

import entite.Reclamation;
import java.io.IOException;
import java.net.URL;
import java.sql.Date;
import java.util.ResourceBundle;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.stage.Stage;
import service.ReclamationService;

/**
 * FXML Controller class
 *
 * @author DELL
 */
public class DashbordReclamationController implements Initializable {
     @FXML
    private TableView<Reclamation> tabrec;
    @FXML
    private TableColumn<Reclamation, Integer> id;
    @FXML
    private TableColumn<Reclamation,String > type;
      @FXML
    private TableColumn<Reclamation,String > description;
    @FXML
    private TableColumn<Reclamation, String> date;
     @FXML
    private TableColumn<Reclamation, String> etat;
  
    @FXML
    private Button refresh;
    @FXML
    private Button delete;
    @FXML
    private Button cancel;
  

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        
    }    
     
    @FXML
    private void Actualiser(ActionEvent event) {
         ReclamationService rs = new ReclamationService();
        ObservableList<Reclamation> or = FXCollections.observableArrayList(rs.readAll());
       id.setCellValueFactory(new PropertyValueFactory<>("id"));
         type.setCellValueFactory(new PropertyValueFactory<>("type"));
        description.setCellValueFactory(new PropertyValueFactory<>("description"));
         date.setCellValueFactory(new PropertyValueFactory<>("date"));
        
       
        tabrec.setItems(or);
    }
    
    
     @FXML
    private void Supprimer(ActionEvent event) {
        ReclamationService cs = new ReclamationService();
        Reclamation r = (Reclamation) tabrec.getSelectionModel().getSelectedItem();
        cs.delete(r.getId());
    }
    
    
     @FXML
      private void modifier(ActionEvent event) {
        Reclamation r = tabrec.getSelectionModel().getSelectedItem();
         

if(r==null){
        
           System.out.println("Aucun reclamation séléctionné");
            Alert alert = new Alert(Alert.AlertType.WARNING);
            alert.setTitle("Erreur");
            alert.setHeaderText(null);
            alert.setContentText("Aucun reclamation séléctionné");

            alert.showAndWait();
     
        }else {
          try {   
        FXMLLoader loader = new FXMLLoader
                        (getClass()
                         .getResource("EditReclamation.fxml"));
        Scene scene=new Scene(loader.load());
        

       EditReclamationController ec= loader.getController();
        Stage stageAff=new Stage();
        stageAff.setScene(scene);
        stageAff.show();
        ((Node) (event.getSource())).getScene().getWindow().hide();
        int as=tabrec.getSelectionModel().getSelectedItem().getId();
        String type = tabrec.getSelectionModel().getSelectedItem().getType();
           String description = tabrec.getSelectionModel().getSelectedItem().getDescription();
              Date date = tabrec.getSelectionModel().getSelectedItem().getDate();
      

        
       
        
        ec.setDAta(tabrec.getSelectionModel().getSelectedItem().getId(),
                tabrec.getSelectionModel().getSelectedItem().getType(),
                tabrec.getSelectionModel().getSelectedItem().getDescription()
                ,date);
                 
                 
       
        } catch(IOException ex)
    {
     System.out.println("eer");
}
        }
     
          
    
    }
    
    
      @FXML
    private void retour(ActionEvent event) throws IOException {
         javafx.scene.Parent tableview = FXMLLoader.load(getClass().getResource("AddReponse.fxml"));
        Scene sceneview = new Scene(tableview);
        Stage window = (Stage)((Node)event.getSource()).getScene().getWindow();
        window.setScene(sceneview);
        window.show();
    }
     @FXML
    private void repondre(ActionEvent event) throws IOException {
        
        Reclamation r = tabrec.getSelectionModel().getSelectedItem();
         

if(r==null){
        
           System.out.println("Aucun reclamation séléctionné");
            Alert alert = new Alert(Alert.AlertType.WARNING);
            alert.setTitle("Erreur");
            alert.setHeaderText(null);
            alert.setContentText("Aucun reclamation séléctionné");

            alert.showAndWait();
     
        }
else {
    try {
        
         FXMLLoader loader = new FXMLLoader
                        (getClass()
                         .getResource("AddReponse.fxml"));
        Scene scene=new Scene(loader.load());
        AddReponseController ec= loader.getController();
        Stage stageAff=new Stage();
        stageAff.setScene(scene);
        stageAff.show();
        ((Node) (event.getSource())).getScene().getWindow().hide();
        
        ec.setDAta(tabrec.getSelectionModel().getSelectedItem().getId());
                 
              
         
    }
    catch(IOException ex)
    {
     System.out.println("eer");
}
    
}
}
}
