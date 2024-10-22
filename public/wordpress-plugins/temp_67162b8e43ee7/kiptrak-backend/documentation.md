## Table of Contents

1. [Installation](#installation)
2. [Usage](#usage)
    - [Shortcode Format](#shortcode-format)
    - [Example Usage](#example-usage)
3. [Shortcode Attributes](#shortcode-attributes)
4. [Error Handling](#error-handling)
5. [Notes](#notes)

## Installation

To install and activate the Kiptrak plugin, follow these steps:

1. **Upload the Plugin**:

    - Upload the `kiptrak-backend` folder to the `/wp-content/plugins/` directory of your WordPress installation.

2. **Activate the Plugin**:

    - Navigate to the **Plugins** menu in the WordPress admin panel.
    - Locate the **Kiptrak** plugin and click on **Activate**.

3. **Configure Settings**:
    - Go to the **Kiptrak Settings** page in the WordPress admin panel.
    - Enter the required settings, including the backend URL.

## Usage

The Kiptrak plugin uses shortcodes to integrate backend forms into your WordPress pages or posts.

#### **Shortcodes**

The Kiptrak plugin uses shortcodes to embed forms and other elements from the Kiptrak backend app into your WordPress site. To add a shortcode, follow these steps:

1. **Navigate to the Builder Page**:

    - Go to the builder page in the Kiptrak backend application.
    - Locate the form or element you want to embed.
    - Copy the shortcode provided.

2. **Manually Create a Shortcode**:

    - Alternatively, you can create a shortcode manually using the format provided in the shortcode documentation.

    **Shortcode Format:**

    To use the Kiptrak shortcode, you can either copy the provided shortcode from the Kiptrak backend or manually create it. The general format is:

    ```plaintext
    [kiptrak type="form" id="123" key="abc123" order_id="456" stage="2" redirect_url="https://example.com"]
    ```

    To get the order details, you can use the following shortcode in your post or page editor:

    ```plaintext
    [kiptrak type="order" order_id="123"]
    ```

    If you wish to display an order on your redirect_url, use this shortcode on your thank you page:

    ```plaintext
    [kiptrak type="order" order_id="any" stage="thankYou"]

    ```

    **Example Usage:**

    1. **Embedding a Form**:
       To embed a form with a specific ID, you can use the following shortcode in your post or page editor:

        ```plaintext
        [kiptrak type="form" id="123"]
        ```

    2. **Using with Additional Parameters**:
       If you want to include additional parameters such as an order ID or a redirect URL, you can extend the shortcode like this:

        ```plaintext
        [kiptrak type="form" id="123" order_id="456" redirect_url="https://example.com"]
        ```

        **Shortcode Attributes:**

    The shortcode accepts the following attributes:

    | Attribute      | Required    | Description                                                     |
    | -------------- | ----------- | --------------------------------------------------------------- |
    | `type`         | Yes         | Specifies the type of content to embed. Use "form" for forms.   |
    | `id`           | Conditional | The ID of the form to embed. Required if `type` is "form".      |
    | `key`          | Conditional | The key associated with the form. Required if `type` is "form". |
    | `order_id`     | No          | Optional order ID associated with the form.                     |
    | `stage`        | No          | Optional stage identifier for the form.                         |
    | `redirect_url` | No          | URL to redirect to after form submission.                       |

    **Error Handling:**

    - If there is a connection issue or an unexpected HTTP response, it returns a relevant error message.

## Notes

-   Ensure that you have the necessary **API access** from Kiptrak to fully utilize this plugin's capabilities.
-   The plugin is designed to return error messages in a user-friendly format, providing insight into what might be going wrong.

By following the above instructions, you can successfully integrate and use the Kiptrak plugin on your WordPress site. If you encounter any issues, consult the error messages returned by the shortcode for guidance on resolving them.
