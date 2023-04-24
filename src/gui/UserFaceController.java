/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gui;

import java.io.ByteArrayInputStream;
import java.net.URL;
import java.util.ResourceBundle;
import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.stage.FileChooser;
import javafx.stage.Stage;
import service.UserCRUD;
import util.DataSource;

/**
 * FXML Controller class
 *
 * @author aminh
 */
public class UserFaceController implements Initializable {
         private Connection conn;
    private PreparedStatement pst;
   


    @FXML
    private Button uploadButton;
    @FXML
    private Label selectedFileLabel;
    
 
    
     private String email;
    @FXML
    private ImageView imageView;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        // TODO
    }

    @FXML
    private void handleUploadButtonAction(ActionEvent event) {
        
        
        FileChooser fileChooser = new FileChooser();
        fileChooser.setTitle("Select an image file");
        fileChooser.getExtensionFilters().addAll(
                new FileChooser.ExtensionFilter("Image Files", "*.png", "*.jpg", "*.gif"));
        File selectedFile = fileChooser.showOpenDialog(uploadButton.getScene().getWindow());
        if (selectedFile != null) {
            selectedFileLabel.setText(selectedFile.getName());
            try {
conn = util.DataSource.getInstance().getCnx();
 String sql = "UPDATE user SET picture = ? WHERE email = ?";
        pst = conn.prepareStatement(sql);  
                

                byte[] fileBytes = Files.readAllBytes(selectedFile.toPath());
                pst.setBytes(1, fileBytes);
                pst.setString(2, email);
                int rowsUpdated = pst.executeUpdate();

                if (rowsUpdated > 0) {
                    Alert alert = new Alert(Alert.AlertType.INFORMATION);
                    alert.setTitle("Success");
                    alert.setHeaderText(null);
                    alert.setContentText("Image uploaded successfully!");
                    alert.showAndWait();
                } else {
                    Alert alert = new Alert(Alert.AlertType.ERROR);
                    alert.setTitle("Error");
                    alert.setHeaderText(null);
                    alert.setContentText("Failed to upload image.");
                    alert.showAndWait();
                }

            } catch (SQLException | IOException ex) {
                ex.printStackTrace();
                Alert alert = new Alert(Alert.AlertType.ERROR);
                alert.setTitle("Error");
                alert.setHeaderText(null);
                alert.setContentText("An error occurred while uploading the image.");
                alert.showAndWait();
            }
        }
    }

    public void setEmail(String email) {
        this.email = email;

        try  {
            conn = util.DataSource.getInstance().getCnx();
 String sql = "SELECT picture FROM user WHERE email = ?";
        pst = conn.prepareStatement(sql);  
            pst.setString(1, email);
            ResultSet rs = pst.executeQuery();
            if (rs.next()) {
                byte[] imageData = rs.getBytes("picture");
                if (imageData != null) {
                    Image image = new Image(new ByteArrayInputStream(imageData));
                    imageView.setImage(image);
                    imageView.setFitWidth(image.getWidth());
                    imageView.setFitHeight(image.getHeight());
                }
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
            Alert alert = new Alert(Alert.AlertType.ERROR);
            alert.setTitle("Error");
            alert.setHeaderText(null);
            alert.setContentText("An error occurred while retrieving the image.");
            alert.showAndWait();
        }
    }


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