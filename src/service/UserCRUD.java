/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package service;

import entite.User;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import util.DataSource;

/**
 *
 * @author aminh
 */
public class UserCRUD {
    
    
    
        Connection cnx;
         String query;
       public UserCRUD() {
         cnx = DataSource.getInstance().getCnx();
    }
    
    
    
    private static String emailUser = "";
    private static String pwduser = "";

     public boolean loginUser(String email, String password) {
        boolean userexist = false;

        try {
            //**************loginClient**********************************
            String query1 = "SELECT   *  FROM user where email=? and password=? ";
             PreparedStatement pst1= DataSource.getInstance().getCnx().prepareStatement(query1);

            pst1.setString(1, email);
            pst1.setString(2, password);

            ResultSet result = pst1.executeQuery();
            if (result.next()) {
                System.out.println(" User Login Success !");
                //getting username here 
                emailUser = email;
                pwduser=password;
                userexist = true;
            }
            else {
                System.out.println("User Login Failed !");
            }
        }
        catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }

        return userexist;
    }
     
     
     public static void logoutUser() {
    emailUser = "";
    pwduser = "";
    System.out.println("User Logged Out Successfully!");
}
    
    
    public boolean registerUser(String email, String nom, String password) {
    boolean userCreated = false;
    
    try {
        // Check if user already exists
        String checkQuery = "SELECT * FROM user WHERE email=?";
        PreparedStatement checkPst = DataSource.getInstance().getCnx().prepareStatement(checkQuery);
        checkPst.setString(1, email);
        ResultSet checkResult = checkPst.executeQuery();
        
        if (checkResult.next()) {
            // User already exists
            System.out.println("User with email " + email + " already exists!");
        } else {
            // User does not exist, create new user
            String insertQuery = "INSERT INTO user (email,nom, password) VALUES (?, ?, ?)";
            PreparedStatement insertPst = DataSource.getInstance().getCnx().prepareStatement(insertQuery);
            insertPst.setString(1, email);
              insertPst.setString(2, nom);
            insertPst.setString(3, password);
            int rowsAffected = insertPst.executeUpdate();
            
            if (rowsAffected > 0) {
                // User created successfully
                System.out.println("User with email " + email + " created successfully!");
                emailUser = email;
                pwduser = password;
                userCreated = true;
            } else {
                System.out.println("Failed to create user with email " + email + "!");
            }
        }
    } catch (SQLException ex) {
        System.err.println(ex.getMessage());
    }
    
    return userCreated;
}

    
    
    
    
        public static User findUserById(int id) {
    User user = null;
    try {
        String query = "SELECT id, email, nom FROM user WHERE id=?";
        PreparedStatement pst = DataSource.getInstance().getCnx().prepareStatement(query);
        pst.setInt(1, id);
        ResultSet rs = pst.executeQuery();
        if (rs.next()) {
            user = new User();
            user.setId(rs.getInt("id"));
            user.setEmail(rs.getString("email"));
            user.setNom(rs.getString("nom"));
          
     
        }
    } catch (SQLException ex) {
        System.out.println(ex.getMessage());
    }
    return user;
}
    
    
    
    
    
    
    
    
    
    
    
    
    
        public ObservableList<User> consulterUser() {
        List<User> listUser = new ArrayList<>();
        String query="";
        

            query ="SELECT * FROM user";
        
        if (query !=""){
        try {

            Statement st = cnx.createStatement();
            ResultSet res = st.executeQuery(query);

            while (res.next()) {
                User c = new User();

                c.setId(res.getInt(1));
                 c.setEmail(res.getString("email"));
                c.setNom(res.getString("nom"));
               
                
      
               
               

                listUser.add(c);
            }

        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
        }
        ObservableList<User> observableList = FXCollections.observableList(listUser);
        return observableList;
    }
    
    
    
    
    
    
    
     
  public static void deleteUser(int id) {
    try {
        String requete = "DELETE FROM user WHERE id=?";
        PreparedStatement pstDon = DataSource.getInstance().getCnx().prepareStatement(requete);
        pstDon.setInt(1, id);
        pstDon.executeUpdate();
   
        System.out.println("user supprimé!");
    } catch (SQLException ex) {
        System.out.println(ex.getMessage());
    }
}
    
    
    
    
  
  
  
  
  
  
  
  
  
       
public static void modifierUser(User t) {
    try {
        String requete = "UPDATE user SET email=?, nom=? WHERE id=?" ;
        PreparedStatement pst= DataSource.getInstance().getCnx().prepareStatement(requete);

        pst.setString(1, t.getEmail());
        pst.setString(2, t.getNom());
        pst.setInt(3, t.getId());
        pst.executeUpdate();
        System.out.println("user modifié!");
    } catch (SQLException ex) {
        System.out.println(ex.getMessage());
    }
}
    
    
}

