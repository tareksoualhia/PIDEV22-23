/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Services;

import Entites.Evenement;
import Entites.Sponsor;
import Utils.MyConnection;
import java.sql.Connection;
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
public class SponsorService implements IService<Sponsor>{
    
    Connection conn;
    PreparedStatement ste;

    public SponsorService() {
        conn=MyConnection.getInstance().getConnection();
    }

    @Override
    public void add(Sponsor entity) {
        String sql = "insert into Sponsor(event_id,nom,budget) Values(?,?,?)";
        try {
            ste=conn.prepareStatement(sql);
            ste.setFloat(3, entity.getBudget());
            ste.setString(2, entity.getNom().toString());
            ste.setInt(1, entity.getEventId());
               System.out.println(ste.toString());
            
            ste.executeUpdate();
            System.out.println("Sponsor Ajouté");
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        } 
    }

    @Override
    public void update(Sponsor entity) {
        String sql = "update  Sponsor set event_id=?,nom=?,budget=? where id=?";
        try {
            ste=conn.prepareStatement(sql);
            ste.setFloat(3, entity.getBudget());
            ste.setString(2, entity.getNom().toString());
            ste.setInt(1, entity.getEventId());
            ste.setInt(4, entity.getId());
               
            
            ste.executeUpdate();
            System.out.println("Sponsor Ajouté");
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        } 
    }

    @Override
    public void Delete(int id) {
         String sql = "DELETE from Sponsor where id= '"+id+"' "; 
        try{

            
           Statement st= conn.createStatement();       
           st.executeUpdate(sql);
           System.out.println("Sponsor supprimé avec succés !");
       }catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }  
    }

    @Override
    public List<Sponsor> Show() {
        Services.EvenementService sr=new EvenementService();
        ObservableList<Sponsor> Sponsorlist = FXCollections.observableArrayList();
        
        try{
        Statement st= conn.createStatement();
        String query = "select * from Sponsor";
        
            ResultSet rs;
        rs = st.executeQuery(query);
        Sponsor eq;
        while (rs.next()) {
           eq = new Sponsor(rs.getInt("id"),rs.getInt("event_id"),rs.getString("nom"),rs.getFloat("budget")); 
            eq.setEvent_nom(sr.getById(eq.getEventId()).getNom());
            Sponsorlist.add(eq);

        }
         return Sponsorlist;    
         }catch(SQLException ex){
                         System.out.println(ex.getMessage());

         }
        return Sponsorlist;
    }

    @Override
    public Sponsor getById(int id) {
        Services.EvenementService sr=new EvenementService();
        Sponsor eq=new Sponsor();
        try{
        Statement st= conn.createStatement();
        String query = "select * from Sponsor where id="+id+"";
        
            ResultSet rs;
        rs = st.executeQuery(query);
        while (rs.next()) {
           eq = new Sponsor(rs.getInt("id"),rs.getInt("event_id"),rs.getString("nom"),rs.getFloat("budget")); 
            eq.setEvent_nom(sr.getById(eq.getEventId()).getNom());

        }
         return eq;    
         }catch(SQLException ex){
                         System.out.println(ex.getMessage());

         }
        return eq;
    }
    
    
    public Evenement getEventByName(String id) {
        
        Evenement comp=new Evenement();
        Services.EvenementService sr=new EvenementService();
        try{
        Statement st= conn.createStatement();
        String query = "select * from evenement where title='"+id+"'";
            System.out.println(query);
        
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
