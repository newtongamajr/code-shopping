import { Component, OnInit } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  credenciais =
    {
      email : 'mail@mail.com',
      password : 'secret'
    };
  private token: string;

  constructor(private http: HttpClient)
  {
  }

  ngOnInit() {
  }

  submit()
  {
    this.http.post<any>('http://codeshopping.teste:8080/api/login', this.credenciais)
      .subscribe(data =>
      {
        this.token = data.token;
        this.http.get('http://codeshopping.teste:8080/api/categories',
    {
              headers:
                {
                  'Authorization': `Bearer ${this.token}`
                }
            })
          .subscribe(data => console.log(data))
      });
    return false;
  }
}