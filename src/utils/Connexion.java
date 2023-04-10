/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package utils;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

/**
 *
 * @author tarek
 */
public class Connexion {
 
     public String url="jdbc:mysql://localhost:3306/tarek";
    public String login="root" ;
    public String pwd= "";  
    public Connection cnx;
     
    public Connexion(){
        try {
            cnx = DriverManager.getConnection(url,login, pwd);
             System.out.println("connexion etablie!");
        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    
    
    }
    public Connection getCnx(){
    return cnx;
}


}
