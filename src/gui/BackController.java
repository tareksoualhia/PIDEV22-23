/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gui;

import Entites.Evenement;
import Entites.Sponsor;
import Services.EvenementService;
import Services.SponsorService;
import java.net.URL;
import java.sql.Date;
import java.util.Optional;
import java.util.ResourceBundle;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.ButtonType;
import javafx.scene.control.ComboBox;
import javafx.scene.control.DatePicker;
import javafx.scene.control.Label;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextField;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.Pane;
import javafx.stage.Modality;
import javafx.stage.Stage;

/**
 * FXML Controller class
 *
 * @author houss
 */
public class BackController implements Initializable {

    @FXML
    private TableColumn<Evenement, String> colNom;
    @FXML
    private TableColumn<Evenement, String> colDesc;
    @FXML
    private TableColumn<Evenement, Date> colDebut;
    @FXML
    private TableColumn<Evenement, Date> colFin;
    @FXML
    private Button btnMenuEvent;
    @FXML
    private Button btnMenuSponsor;
    @FXML
    private Pane pnSponsor;
    @FXML
    private TableView<Sponsor> tvSponsor;
    @FXML
    private TableColumn<Sponsor, String> colNomSponsor;
    @FXML
    private Button btnAjoutSponsor;
    @FXML
    private Button btnModifySponsor;
    @FXML
    private Button btnSupprimerSponsor;
    @FXML
    private Label lbidSp;
    @FXML
    private Pane pnFormSponsor;
    @FXML
    private TextField tfNomSponsor;
    @FXML
    private TextField tfBudgetSp;
    @FXML
    private ComboBox<String> tfEventSp;
    @FXML
    private Button btnConfSp;
    @FXML
    private Pane pnEvent;
    @FXML
    private TableView<Evenement> tvEvent;
    @FXML
    private Button btnAjoutEvent;
    @FXML
    private Button btnModifyEvent;
    @FXML
    private Button btnSupprimerEvent;
    @FXML
    private Label lbIdEvent;
    @FXML
    private Pane pnFormEvent;
    @FXML
    private TextField tfNomEvent;
    @FXML
    private TextField tfDescEvent;
    @FXML
    private DatePicker tfDebutEvent;
    @FXML
    private DatePicker tfFinEvent;
    @FXML
    private Label lbTitleAjoutEvent;
    @FXML
    private Label lbTitleModifyEvent;
    @FXML
    private Button btnConfEvent;
    @FXML
    private Label lbTitleAjoutSp;
    @FXML
    private Label lbTitleModifySp;
    @FXML
    private TableColumn<Sponsor, String> colEventSponsor;
    @FXML
    private TableColumn<Sponsor, Float> colBudgetSponsor;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        pnEvent.toFront();
        fnEventShow();
        // TODO
        fnSpShow();
        ObservableList<String> valuesList = FXCollections.observableArrayList();
        EvenementService sr=new EvenementService();
        for (Evenement comp : sr.Show()) {
            valuesList.add(comp.getNom());
        }
        tfEventSp.setItems(valuesList);
    }    
    
    public void fnEventShow(){
        EvenementService sr=new EvenementService();
         ObservableList<Evenement> list = FXCollections.observableArrayList(sr.Show());;
    
     colNom.setCellValueFactory(new PropertyValueFactory<>("nom"));       
     colDesc.setCellValueFactory(new PropertyValueFactory<>("Description"));        
     colDebut.setCellValueFactory(new PropertyValueFactory<>("date_debut"));   
     colFin.setCellValueFactory(new PropertyValueFactory<>("date_fin"));   

        
     tvEvent.setItems(list);}
    
    
    public void fnSpShow(){
        SponsorService sr=new SponsorService();
         ObservableList<Sponsor> list = FXCollections.observableArrayList(sr.Show());;
    
     colNomSponsor.setCellValueFactory(new PropertyValueFactory<>("Nom"));       
     colBudgetSponsor.setCellValueFactory(new PropertyValueFactory<>("budget"));        
     colEventSponsor.setCellValueFactory(new PropertyValueFactory<>("event_nom"));   

        
     tvSponsor.setItems(list);}




    @FXML
    private void fnSelectedCompetition(MouseEvent event) {
        Evenement rec= tvEvent.getSelectionModel().getSelectedItem();
        lbIdEvent.setText(rec.getId()+"");
    }




    @FXML
    private void fnSelectedEquipe(MouseEvent event) {
         Sponsor rec= tvSponsor.getSelectionModel().getSelectedItem();
        lbidSp.setText(rec.getId()+"");
    }




    @FXML
    private void fnMenuEvent(ActionEvent event) {
         pnEvent.toFront();
    }

    @FXML
    private void fnMeuSponsor(ActionEvent event) {
        pnSponsor.toFront();
    }

    @FXML
    private void fnAjoutSponsor(ActionEvent event) {
         pnFormSponsor.toFront();
        lbTitleAjoutSp.setVisible(true);
        lbTitleModifySp.setVisible(false);
    }

    @FXML
    private void fnModifySponsor(ActionEvent event) {
         pnFormSponsor.toFront();
        lbTitleAjoutSp.setVisible(false);
        lbTitleModifySp.setVisible(true);
        SponsorService eq =new SponsorService();
        Sponsor e=eq.getById(Integer.parseInt(lbidSp.getText()));
        tfNomSponsor.setText(e.getNom());
        tfBudgetSp.setText(e.getBudget()+"");
        tfEventSp.setValue(e.getEvent_nom());
    }

    @FXML
    private void fnSupprimerSponsor(ActionEvent event) {
        SponsorService cs =new SponsorService();
        cs.Delete(Integer.parseInt(lbidSp.getText()));
        fnSpShow();
    }

    @FXML
    private void fnConfSp(ActionEvent event) {
        SponsorService cs=new SponsorService();
        System.out.println(lbTitleAjoutSp.isVisible());
        System.out.println(lbidSp.getText());
        if(lbTitleAjoutSp.isVisible()){
            Sponsor c =new Sponsor();
            
            
            
            String ERROR_MSG="";
            if("".equals(tfNomSponsor.getText())){
                ERROR_MSG="Le champs nom de dois pas pas étre null";
            }
            if("".equals(tfBudgetSp.getText())){
                ERROR_MSG="Le champs budget de dois pas pas étre null";
            }
            if("".equals(tfEventSp.getValue())){
                ERROR_MSG="Le champs evenement de dois pas pas étre null";
            }
            if(!"".equals(ERROR_MSG)){
            Stage window = (Stage)pnFormSponsor.getScene().getWindow();
            Alert.AlertType type=Alert.AlertType.ERROR;
            Alert alert=new Alert(type,"");
            
            alert.initModality(Modality.APPLICATION_MODAL);
            alert.initOwner(window);
            
            alert.getDialogPane().setContentText(ERROR_MSG);
            alert.getDialogPane().setHeaderText("");
            
            Optional<ButtonType> result = alert.showAndWait();
            if (result.get()==ButtonType.OK) {
                
                pnFormSponsor.toFront();
        }}else{
                c.setNom(tfNomSponsor.getText());
            c.setBudget(Float.parseFloat(tfBudgetSp.getText()));
            c.setEventId(cs.getEventByName(tfEventSp.getValue()).getId());
               cs.add(c); 
               tfNomSponsor.setText("");
            tfBudgetSp.setText("");
            tfEventSp.setValue(null);
            pnSponsor.toFront();
            
        fnSpShow();
            }
            
            
            
            
        }else{
            Sponsor c =new Sponsor();
            
            
            
            String ERROR_MSG="";
            if("".equals(tfNomSponsor.getText())){
                ERROR_MSG="Le champs nom de dois pas pas étre null";
            }
            if("".equals(tfBudgetSp.getText())){
                ERROR_MSG="Le champs budget de dois pas pas étre null";
            }
            if("".equals(tfEventSp.getValue())){
                ERROR_MSG="Le champs evenement de dois pas pas étre null";
            }
            if(!"".equals(ERROR_MSG)){
            Stage window = (Stage)pnFormSponsor.getScene().getWindow();
            Alert.AlertType type=Alert.AlertType.ERROR;
            Alert alert=new Alert(type,"");
            
            alert.initModality(Modality.APPLICATION_MODAL);
            alert.initOwner(window);
            
            alert.getDialogPane().setContentText(ERROR_MSG);
            alert.getDialogPane().setHeaderText("");
            
            Optional<ButtonType> result = alert.showAndWait();
            if (result.get()==ButtonType.OK) {
                
                pnFormSponsor.toFront();
        }}else{
                c.setNom(tfNomSponsor.getText());
            c.setBudget(Float.parseFloat(tfBudgetSp.getText()));
            c.setEventId(cs.getEventByName(tfEventSp.getValue()).getId());
                c.setId(Integer.parseInt(lbidSp.getText()));
               cs.update(c); 
               tfNomSponsor.setText("");
            tfBudgetSp.setText("");
            tfEventSp.setValue(null);
            pnSponsor.toFront();
            
        fnSpShow();
            }
        }
        
    }

    @FXML
    private void fnAjoutEvent(ActionEvent event) {
        pnFormEvent.toFront();
        lbTitleAjoutEvent.setVisible(true);
        lbTitleModifyEvent.setVisible(false);
    }

    @FXML
    private void fnModifyEvent(ActionEvent event) {
         pnFormEvent.toFront();
        lbTitleAjoutEvent.setVisible(false);
        lbTitleModifyEvent.setVisible(true);
        EvenementService cs=new EvenementService();
        Evenement rec=cs.getById(Integer.parseInt(lbIdEvent.getText()));
        tfNomEvent.setText(rec.getNom());
        tfDescEvent.setText(rec.getDescription());
       tfDebutEvent.setValue(rec.getDate_debut().toLocalDate());
        tfFinEvent.setValue(rec.getDate_fin().toLocalDate());
    }

    @FXML
    private void fnSupprimerEvent(ActionEvent event) {
        EvenementService cs=new EvenementService();
        cs.Delete(Integer.parseInt(lbIdEvent.getText()));
        fnEventShow();
    }

    @FXML
    private void fnConfEvent(ActionEvent event) {
        EvenementService cs=new EvenementService();
        if(lbTitleAjoutEvent.isVisible()){
            Evenement c =new Evenement();
            
            String ERROR_MSG="";
            if("".equals(tfNomEvent.getText())){
                ERROR_MSG="Le champs nom de dois pas pas étre null";
                System.out.println();
                System.out.println(ERROR_MSG);
            }
            if("".equals(tfDescEvent.getText())){
                ERROR_MSG="Le champs description de dois pas pas étre null";
                System.out.println(ERROR_MSG);
            }
            if(tfDebutEvent.getValue()==null){
                ERROR_MSG="Le champs date debut de dois pas pas étre null";
                System.out.println(ERROR_MSG);
            }
            if(tfFinEvent.getValue()==null){
                ERROR_MSG="Le champs date fin de dois pas pas étre null";
                System.out.println(ERROR_MSG);
            }
            if(!"".equals(ERROR_MSG)){
            Stage window = (Stage)pnFormEvent.getScene().getWindow();
            Alert.AlertType type=Alert.AlertType.ERROR;
            Alert alert=new Alert(type,"");
            
            alert.initModality(Modality.APPLICATION_MODAL);
            alert.initOwner(window);
            
            alert.getDialogPane().setContentText(ERROR_MSG);
            alert.getDialogPane().setHeaderText("");
            
            Optional<ButtonType> result = alert.showAndWait();
            if (result.get()==ButtonType.OK) {
                
                pnFormEvent.toFront();
        }}else{
                c.setNom(tfNomEvent.getText());
            c.setDescription(tfDescEvent.getText());
            c.setDate_debut(Date.valueOf(tfDebutEvent.getValue()));
            c.setDate_fin(Date.valueOf(tfFinEvent.getValue()));
               cs.add(c); 
               tfNomEvent.setText("");
            tfDescEvent.setText("");
            tfDebutEvent.setValue(null);
            tfFinEvent.setValue(null);
            pnEvent.toFront();
            fnEventShow();
            }
            
            
        }else{
            Evenement c =new Evenement();
            
            String ERROR_MSG="";
            if("".equals(tfNomEvent.getText())){
                ERROR_MSG="Le champs nom de dois pas pas étre null";
                System.out.println();
                System.out.println(ERROR_MSG);
            }
            if("".equals(tfDescEvent.getText())){
                ERROR_MSG="Le champs description de dois pas pas étre null";
                System.out.println(ERROR_MSG);
            }
            if(tfDebutEvent.getValue()==null){
                ERROR_MSG="Le champs date debut de dois pas pas étre null";
                System.out.println(ERROR_MSG);
            }
            if(tfFinEvent.getValue()==null){
                ERROR_MSG="Le champs date fin de dois pas pas étre null";
                System.out.println(ERROR_MSG);
            }
            if(!"".equals(ERROR_MSG)){
            Stage window = (Stage)pnFormEvent.getScene().getWindow();
            Alert.AlertType type=Alert.AlertType.ERROR;
            Alert alert=new Alert(type,"");
            
            alert.initModality(Modality.APPLICATION_MODAL);
            alert.initOwner(window);
            
            alert.getDialogPane().setContentText(ERROR_MSG);
            alert.getDialogPane().setHeaderText("");
            
            Optional<ButtonType> result = alert.showAndWait();
            if (result.get()==ButtonType.OK) {
                
                pnFormEvent.toFront();
        }}else{
                c.setNom(tfNomEvent.getText());
            c.setDescription(tfDescEvent.getText());
            c.setDate_debut(Date.valueOf(tfDebutEvent.getValue()));
            c.setDate_fin(Date.valueOf(tfFinEvent.getValue()));
             c.setId(Integer.parseInt(lbIdEvent.getText()));
               cs.update(c); 
               tfNomEvent.setText("");
            tfDescEvent.setText("");
            tfDebutEvent.setValue(null);
            tfFinEvent.setValue(null);
            pnEvent.toFront();
            fnEventShow();
            }

        }
    }
    
}
