# Nova Page Flexible Models

A package for Laravel Nova which extends [Nova Page](https://github.com/whitecube/nova-page) and [Nova Flexible Content](https://github.com/whitecube/nova-flexible-content) and enables you to relate Laravel models to your Nova Pages using Flexible Content.

## Installation

```bash
composer require wqa/nova-page-flexible-models
```

## Usage

In your page template class you must include the `HasFlexibleModels` trait.

To add a flexible model field, use the `addFlexibleModelField` method in your fields definition.

You can use the `getFlexibleModels` method to get the models back from the page. It's useful to do this using a helper method as shown below to keep your blade files clean.

```php
class AboutPage extends Template
{
    use WQA\NovaPageFlexibleModels\HasFlexibleModels;

    public function fields(Request $request)
    {
        return [
            Panel::make('Tesimonials', [
                $this->addFlexibleModelField('Testimonials', 'testimonials', Testimonial::class, 'author'),
            ])
        ];
    }

    public function testimonials(): Collection
    {
        return $this->getFlexibleModels('testimonials', Testimonial::class);
    }
}
```

To access the testimonials in a blade view you can call the helper method as above which will give you a collection of whichever model you have specified.

```blade
@foreach (Page::testimonials() as $testimonial)
    <blockquote>
        {{ $testimonial->body }}
        <cite>{{ $testimonial->author }}</cite>
    </blockquote>
@endforeach
```
