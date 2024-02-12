import { Injectable } from '@angular/core'; 
@Injectable({
  providedIn: 'root'
})
export class LanguageService {

  private languages: any = {
    'en': {
      'hello': 'Hello',
      'world': 'World'
    },
    'id': {
      'hello': 'Halo',
      'world': 'Dunia'
    }, 
  };

  constructor() { }

  get(word: string): string {
    const language = "en";
    if (this.languages[language] && this.languages[language][word]) {
      return this.languages[language][word];
    } else {
      // Default to English if the language or word is not found
      return this.languages['en'][word];
    }
  }

}
