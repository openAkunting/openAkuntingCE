import { Component, ViewEncapsulation } from '@angular/core';
import { Select2Data } from 'ng-select2-component';

@Component({
  selector: 'app-test',
  templateUrl: './test.component.html',
  styleUrls: ['./test.component.css'],
})
export class TestComponent {
  value: any;
  data: Select2Data = [
    {
      label: "Red",
      data: {
        name: "(Red)"
      },
      options: [
        {
          value: "hibiscus",
          label: "Hibiscus",
          data: {
            color: "red",
            name: "Hibiscus"
          },
          templateId: "template1",
          id: "option-hibiscus"
        },
        {
          value: "marigold",
          label: "Marigold",
          data: {
            color: "red",
            name: "Marigold"
          },
          templateId: "template2",
          id: "option-marigold"
        }
      ]
    },
    {
      label: "Yellow",
      data: {
        name: "(Yellow)"
      },
      options: [
        {
          value: "sunflower",
          label: "Sunflower",
          data: {
            color: "yellow",
            name: "Sunflower"
          },
          templateId: "template3",
          id: "option-sunflower"
        }
      ]
    },
    {
      label: "White",
      data: {
        name: "(White)"
      },
      options: [
        {
          value: "heliotrope",
          label: "Heliotrope",
          data: {
            color: "white",
            name: "Heliotrope"
          },
          templateId: "template1",
          id: "option-heliotrope"
        },
        {
          value: "lily",
          label: "Lily",
          data: {
            color: "white",
            name: "Lily"
          },
          templateId: "template2",
          id: "option-lily"
        },
        {
          value: "petunia",
          label: "Petunia",
          data: {
            color: "white",
            name: "Petunia"
          },
          templateId: "template3",
          id: "option-petunia"
        }
      ]
    }
  ];
  update(data: any) {
    console.log(data);
    this.data = [
      {
        value: 'heliotrope',
        label: 'Heliotrope',
        data: { color: 'white', name: 'Heliotrope' },
      },
      {
        value: 'hibiscus',
        label: 'Hibiscus',
        data: { color: 'red', name: 'Hibiscus' },
      },
    ];
  }
}
