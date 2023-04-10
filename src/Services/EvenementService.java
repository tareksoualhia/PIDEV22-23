/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Services;

import Entites.Evenement;
import Utils.MyConnection;
import java.sql.Connection;
import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.List;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

/**
 *
 * @author houss
 */
public class EvenementService implements IService<Evenement>{

    Connection conn;
    PreparedStatement ste;

    public EvenementService() {
        conn=MyConnection.getInstance().getConnection();
    }
    
    
    @Override
    public void add(Evenement entity) {
String sql = "insert into evenement(title,description,date_debut,date_fin) Values(?,?,?,?)";
        try {
            ste=conn.prepareStatement(sql);
            ste.setString(2, entity.getDescription());
            ste.setString(1, entity.getNom().toString());
            ste.setDate(3, entity.getDate_debut());
            ste.setDate(4, entity.getDate_fin());
               
            System.out.println(ste.toString());
            ste.executeUpdate();
            System.out.println("Evenement Ajouté");
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        } 
    }

    @Override
    public void update(Evenement entity) {
        String sql = "update  Evenement set title= ? ,description= ? ,date_debut= ?,date_fin=? where id= ?";
        try {
            ste=conn.prepareStatement(sql);
            ste.setString(2, entity.getDescription());
            ste.setString(1, entity.getNom().toString());
            ste.setDate(3, entity.getDate_debut());
            ste.setDate(4, entity.getDate_fin());
            ste.setInt(5, entity.getId());
               
            
            ste.executeUpdate();
            System.out.println("Evenement Updated");
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        } 
    }

    @Override
    public void Delete(int id) {
         String sql = "DELETE from Evenement where id= '"+id+"' "; 
        String sql1="DELETE from sponsor where event_id= '"+id+"' "; 
        try{

            
           Statement st= conn.createStatement();
           st.executeUpdate(sql1);        
           st.executeUpdate(sql);
           System.out.println("Evenement supprimé avec succés !");
       }catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }  
    }

    @Override
    public List<Evenement> Show() {
        ObservableList<Evenement> Competitionlist = FXCollections.observableArrayList();
        try{
        Statement st= conn.createStatement();
        String query = "select * from Evenement";
        
        ResultSet rs;
        rs = st.executeQuery(query);
        Evenement comp;
        while (rs.next()) {
           comp = new Evenement(rs.getInt("id"),rs.getString("title"),rs.getString("description")
                   ,rs.getDate("date_debut"),rs.getDate("date_fin")); 
            Competitionlist.add(comp);

        }
         return Competitionlist;    
         }catch(SQLException ex){
                         System.out.println(ex.getMessage());

         }
        return Competitionlist;
    }

    @Override
    public Evenement getById(int id) {
        Evenement comp=new Evenement();
        try{
        Statement st= conn.createStatement();
        String query = "select * from Evenement where id="+id+"";
        
        ResultSet rs;
        rs = st.executeQuery(query);
        
        while (rs.next()) {
           comp = new Evenement(rs.getInt("id"),rs.getString("title"),rs.getString("description")
                   ,rs.getDate("date_debut"),rs.getDate("date_fin")); 
            

        }
         return comp;    
         }catch(SQLException ex){
                         System.out.println(ex.getMessage());

         }
        return comp;
    }
    
}
