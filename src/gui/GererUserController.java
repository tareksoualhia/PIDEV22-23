/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gui;


import entite.User;
import java.io.IOException;
import java.net.URL;
import java.util.List;
import java.util.ResourceBundle;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.input.MouseEvent;
import javafx.stage.Modality;
import javafx.stage.Stage;
import service.UserCRUD;

/**
 * FXML Controller class
 *
 * @author aminh
 */
public class GererUserController implements Initializable {

    @FXML
    private TableView<User> table;
    @FXML
    private TableColumn<User, String> colId;
    @FXML
    private TableColumn<User, String> colEmail;
    @FXML
    private TableColumn<User, String> colNom;

    
        ObservableList<User> obl = FXCollections.observableArrayList();

    /**
     * Initializes the controller class.
     * @param url
     * @param rb
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
          getListUser();
        // TODO
    }    
    
    
            public void getListUser() {
        UserCRUD cmd = new UserCRUD();
        colId.setCellValueFactory(new PropertyValueFactory<>("id"));
            colEmail.setCellValueFactory(new PropertyValueFactory<>("email"));
                  colNom.setCellValueFactory(new PropertyValueFactory<>("nom"));
     
    
  
    
        
        // Display row data
        List<User> listUser = cmd.consulterUser();
        ObservableList<User> observableList = FXCollections.observableList(listUser);
         table.setItems(observableList);
    }

    @FXML
    private void getDetailCmd(MouseEvent event) {
        
        
    }

    @FXML
    private void refrechdon(ActionEvent event) {
        
                 UserCRUD cmd = new UserCRUD();
    List<User> listUser = cmd.consulterUser();

    // Update the table view with the new data
    table.getItems().clear();
    table.getItems().addAll(listUser);
    }

    @FXML
    private void modifierUser(ActionEvent event) throws IOException {
                 FXMLLoader loader = new FXMLLoader(getClass().getResource("ModifyUser.fxml"));
       Parent root = loader.load();
       Scene scene = new Scene(root);
       Stage popupStage = new Stage();
       popupStage.initModality(Modality.WINDOW_MODAL);
       popupStage.initOwner(((Node) event.getSource()).getScene().getWindow());
       popupStage.setScene(scene);
       popupStage.show();
    }

  @FXML
private void LogoutUser(ActionEvent event) {
    // Call the logoutUser method to clear the stored user information
     UserCRUD cmd = new UserCRUD();
    UserCRUD.logoutUser();

    // You can also redirect the user to the login page or perform any other necessary actions
    // For example, assuming you have a Login.fxml file in your resources folder:
    try {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("Login.fxml"));
        Parent root = loader.load();
        Scene scene = new Scene(root);
        Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
        stage.setScene(scene);
        stage.show();
    } catch (IOException e) {
        e.printStackTrace();
    }
}

    
}
