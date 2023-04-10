/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gui;

import entite.Reponse;
import entite.ReponseRec;
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
import service.ReponseService;

/**
 * FXML Controller class
 *
 * @author DELL
 */
public class DashbordReponseController implements Initializable {
      @FXML
    private TableView<ReponseRec> tabrec;
    @FXML
    private TableColumn<ReponseRec, Integer> id;
    @FXML
    private TableColumn<ReponseRec,String > type;
      @FXML
    private TableColumn<ReponseRec,String > description;
    @FXML
    private TableColumn<ReponseRec, String> date;
     @FXML
    private TableColumn<ReponseRec, String> etat;
        @FXML
    private TableColumn<ReponseRec, String> reponse;
  
    @FXML
    private Button refresh;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        // TODO
    } 
      @FXML
    private void Actualiser(ActionEvent event) {
         ReponseService rs = new ReponseService();
        ObservableList<ReponseRec> or = FXCollections.observableArrayList(rs.getAll());
       
       id.setCellValueFactory(new PropertyValueFactory<>("id"));
         type.setCellValueFactory(new PropertyValueFactory<>("type"));
        description.setCellValueFactory(new PropertyValueFactory<>("description"));
         date.setCellValueFactory(new PropertyValueFactory<>("date"));
          etat.setCellValueFactory(new PropertyValueFactory<>("etat"));
           reponse.setCellValueFactory(new PropertyValueFactory<>("reponse"));
        
       
        tabrec.setItems(or);
    }
    @FXML
    private void Supprimer(ActionEvent event) {
        ReponseService cs = new ReponseService();
        ReponseRec r = (ReponseRec) tabrec.getSelectionModel().getSelectedItem();
        cs.delete(r.getId());
    }
    
    
     @FXML
      private void modifier(ActionEvent event) {
       ReponseRec r = tabrec.getSelectionModel().getSelectedItem();
         

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
                         .getResource("EditReponse.fxml"));
        Scene scene=new Scene(loader.load());
        

       EditReponseController ec= loader.getController();
        Stage stageAff=new Stage();
        stageAff.setScene(scene);
        stageAff.show();
        ((Node) (event.getSource())).getScene().getWindow().hide();
        int as=tabrec.getSelectionModel().getSelectedItem().getId();
      
              String etat = tabrec.getSelectionModel().getSelectedItem().getEtat();
              String reponse = tabrec.getSelectionModel().getSelectedItem().getReponse();
      

        
       
        
       ec.setDAta(tabrec.getSelectionModel().getSelectedItem().getId(),
                tabrec.getSelectionModel().getSelectedItem().getEtat(),
                tabrec.getSelectionModel().getSelectedItem().getReponse()
       );
                 
                 
       
        } catch(IOException ex)
    {
     System.out.println("eer");
}
        }
     
          
    
    }
}
    

