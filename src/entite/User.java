/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package entite;

/**
 *
 * @author aminh
 */
public class User {
      private int id;
    private String email;
    private String nom;
    private String roles;
    private String password;

    public User() {
    }

    public User(int id, String email, String nom, String roles, String password) {
        this.id = id;
        this.email = email;
        this.nom = nom;
        this.roles = roles;
        this.password = password;
    }

    public User(String email, String nom, String roles, String password) {
        this.email = email;
        this.nom = nom;
        this.roles = roles;
        this.password = password;
    }

    public User(String email, String password) {
        this.email = email;
        this.password = password;
    }

    public User(String email, String nom, String password) {
        this.email = email;
        this.nom = nom;
        this.password = password;
    }

    public User(int id, String email, String nom) {
        this.id = id;
        this.email = email;
        this.nom = nom;
    }
    

    public int getId() {
        return id;
    }

    public String getEmail() {
        return email;
    }

    public String getNom() {
        return nom;
    }

    public String getRoles() {
        return roles;
    }

    public String getPassword() {
        return password;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public void setRoles(String roles) {
        this.roles = roles;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    @Override
    public String toString() {
        return "User{" + "id=" + id + ", email=" + email + ", nom=" + nom + ", roles=" + roles + ", password=" + password + '}';
    }
    
    
}
