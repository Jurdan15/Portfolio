using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Net.Http.Json;
using Microsoft.Maui.Controls;
using MySql.Data.MySqlClient;
using Microsoft.Maui.ApplicationModel.DataTransfer;
using Microsoft.Maui.ApplicationModel;

namespace bot;

public partial class MainPage : ContentPage
{
    private readonly HttpClient _httpClient = new();
    private readonly string rasaURL = "http://localhost:5005/webhooks/rest/webhook";
    private const string MySqlConnectionString = "server=127.0.0.1;user=root;password=;database=inquiry;";
    private int _sessionId;
    private Dictionary<int, (Button likeBtn, Button dislikeBtn)> _ratingButtons = new();

    public MainPage(int sessionId)
    {
        InitializeComponent();
        _sessionId = sessionId;
        NavigationPage.SetHasBackButton(this, false);

        UpdateThemeToggleState();
        CheckUserConsent();
    }

    private void UpdateThemeToggleState()
    {
        ThemeIcon.Text = ThemeManager.IsDarkMode ? "☀️" : "🌙";
        ThemeToggleThumb.HorizontalOptions = ThemeManager.IsDarkMode ? LayoutOptions.End : LayoutOptions.Start;
    }

    private async void CheckUserConsent()
    {
        bool consentGiven = await DisplayAlert(
            "Notice",
            "Your messages will be recorded for quality assurance and to improve our services. Do you agree to continue?",
            "I Agree", "Go Back");

        if (consentGiven)
        {
            InitializeDatabaseColumns();
            DisplayInitialGreeting();
        }
        else
        {
            await Navigation.PushAsync(new WelcomePage());
        }
    }

    private void DisplayInitialGreeting()
    {
        addMsg("Hello! I am the CSU-Gonzaga's FAQ Chatbot.\n\nI will assist you with your inquiries regarding student services in the Admission, Registrar, Guidance, and Student Development and Welfare Offices (OSDW). How may I help you?", isUser: false);
        addMsg("Don't know what to ask? Try these sample questions:\n> What are the requirements for CAT (College Admission Test)?\n> How to enroll as a transferee?\n> Where to get the certificate of enrollment?", isUser: false);
        addMsg("Please note: Your messages will be recorded for quality assurance and to improve our services.", isUser: false);
    }

    private void InitializeDatabaseColumns()
    {
        try
        {
            using var conn = new MySqlConnection(MySqlConnectionString);
            conn.Open();

            var alterCmd = new MySqlCommand(
                @"ALTER TABLE response_rating 
                ADD COLUMN IF NOT EXISTS log_id INT,
                ADD CONSTRAINT fk_response_rating_log 
                FOREIGN KEY (log_id) REFERENCES logs(log_id)", conn);
            alterCmd.ExecuteNonQuery();
        }
        catch (Exception ex)
        {
            Console.WriteLine($"Database column initialization error: {ex.Message}");
        }
    }

    private async void OnBackClicked(object sender, EventArgs e)
    {
        await Navigation.PopAsync();
    }

    private async void OnCloseClicked(object sender, EventArgs e)
    {
        bool confirm = await DisplayAlert("Confirmation", "Are you sure you want to stop chatting?", "Yes", "No");
        if (confirm)
        {
            await Navigation.PushAsync(new FeedbackPage(_sessionId));
        }
    }

    private async void OnSendClicked(object sender, EventArgs e)
    {
        var userMsg = MessageEntry.Text?.Trim();
        if (string.IsNullOrWhiteSpace(userMsg))
            return;

        int logId = SaveMessageToDb(userMsg);
        if (logId == -1)
        {
            addMsg("[Error saving message]", isUser: false);
            return;
        }

        addMsg(userMsg, isUser: true);
        MessageEntry.Text = "";

        try
        {
            var response = await _httpClient.PostAsJsonAsync(rasaURL, new
            {
                sender = "user123",
                message = userMsg
            });

            if (response.IsSuccessStatusCode)
            {
                var rasaMessages = await response.Content.ReadFromJsonAsync<List<RasaMessage>>();
                foreach (var rasaMessage in rasaMessages)
                {
                    addMsg(rasaMessage.Text, isUser: false, logId: logId);
                }
            }
            else
            {
                addMsg("[Error from Rasa]", isUser: false);
            }
        }
        catch (Exception ex)
        {
            addMsg($"[Error: {ex.Message}]", isUser: false);
        }
    }

    private int SaveMessageToDb(string message)
    {
        try
        {
            using var conn = new MySqlConnection(MySqlConnectionString);
            conn.Open();

            string sql = @"INSERT INTO logs (input, timestamp, session_id) 
                         VALUES (@input, @timestamp, @session_id);
                         SELECT LAST_INSERT_ID();";

            using var cmd = new MySqlCommand(sql, conn);
            cmd.Parameters.AddWithValue("@input", message);
            cmd.Parameters.AddWithValue("@timestamp", DateTime.Now);
            cmd.Parameters.AddWithValue("@session_id", _sessionId);

            return Convert.ToInt32(cmd.ExecuteScalar());
        }
        catch (Exception ex)
        {
            Console.WriteLine($"Database error: {ex.Message}");
            return -1;
        }
    }

    private void SaveBotResponseToDb(int logId, string responseText)
    {
        try
        {
            using var conn = new MySqlConnection(MySqlConnectionString);
            conn.Open();

            using var cmd = new MySqlCommand(
                "INSERT INTO responses (log_id, resp_desc, timestamp) VALUES (@log_id, @desc, @time)", conn);

            cmd.Parameters.AddWithValue("@log_id", logId);
            cmd.Parameters.AddWithValue("@desc", responseText);
            cmd.Parameters.AddWithValue("@time", DateTime.Now);

            cmd.ExecuteNonQuery();
        }
        catch (Exception ex)
        {
            Console.WriteLine($"Error saving bot response: {ex.Message}");
        }
    }

    private void addMsg(string text, bool isUser, int logId = -1)
    {
        if (isUser)
        {
            AddUserMessage(text);
        }
        else
        {
            AddBotMessage(text, logId);
        }
        ChatScrollView.ScrollToAsync(ChatStack, ScrollToPosition.End, true);
    }

    private void AddUserMessage(string text)
    {
        var bubble = new Frame
        {
            BackgroundColor = Color.FromArgb("#32620E"),
            CornerRadius = 12,
            Padding = 10,
            Margin = new Thickness(5),
            HorizontalOptions = LayoutOptions.End,
            Content = new Label
            {
                Text = text,
                TextColor = Colors.White,
                FontSize = 14
            }
        };
        ChatStack.Children.Add(bubble);
    }

    private void AddBotMessage(string text, int logId)
    {
        var container = new VerticalStackLayout
        {
            Spacing = 5,
            HorizontalOptions = LayoutOptions.Start
        };

        var bubble = new Frame
        {
            BackgroundColor = Colors.White,
            CornerRadius = 12,
            Padding = 10,
            Margin = new Thickness(5),
            HorizontalOptions = LayoutOptions.Start,
            Content = new Label
            {
                Text = text,
                TextColor = Colors.Black,
                FontSize = 14
            }
        };

        container.Children.Add(bubble);

        if (logId != -1)
        {
            SaveBotResponseToDb(logId, text);
            var buttons = CreateActionButtons(text, logId);
            container.Children.Add(buttons);
        }

        ChatStack.Children.Add(container);
    }

    private HorizontalStackLayout CreateActionButtons(string text, int logId)
    {
        var buttonStack = new HorizontalStackLayout
        {
            Spacing = 10,
            Margin = new Thickness(5, 0, 5, 5)
        };

        var likeButton = new Button
        {
            Text = "👍 Like",
            BackgroundColor = Colors.Transparent,
            TextColor = Colors.Green,
            FontSize = 12,
            Padding = new Thickness(5)
        };

        var dislikeButton = new Button
        {
            Text = "👎 Dislike",
            BackgroundColor = Colors.Transparent,
            TextColor = Colors.Red,
            FontSize = 12,
            Padding = new Thickness(5)
        };

        var copyButton = new Button
        {
            Text = "⎘ Copy",
            BackgroundColor = Colors.Transparent,
            TextColor = Color.FromArgb("#0078D7"),
            FontSize = 12,
            Padding = new Thickness(5)
        };

        var shareButton = new Button
        {
            Text = "↗ Share",
            BackgroundColor = Colors.Transparent,
            TextColor = Color.FromArgb("#FFA500"),
            FontSize = 12,
            Padding = new Thickness(5)
        };

        int messageId = ChatStack.Children.Count;
        likeButton.Clicked += async (s, e) => await RateResponse(messageId, text, 1, logId);
        dislikeButton.Clicked += async (s, e) => await RateResponse(messageId, text, 0, logId);
        copyButton.Clicked += async (s, e) => await CopyToClipboard(text);
        shareButton.Clicked += async (s, e) => await ShareMessage(text);

        _ratingButtons[messageId] = (likeButton, dislikeButton);

        buttonStack.Children.Add(likeButton);
        buttonStack.Children.Add(dislikeButton);
        buttonStack.Children.Add(copyButton);
        buttonStack.Children.Add(shareButton);

        return buttonStack;
    }

    private async Task RateResponse(int messageId, string responseText, int rating, int logId)
    {
        if (_ratingButtons.TryGetValue(messageId, out var buttons))
        {
            buttons.likeBtn.IsVisible = false;
            buttons.dislikeBtn.IsVisible = false;
        }

        try
        {
            using var conn = new MySqlConnection(MySqlConnectionString);
            conn.Open();

            using var cmd = new MySqlCommand(
                "INSERT INTO response_rating " +
                "(response_id, response_description, rating, session_id, log_id, timestamp) " +
                "VALUES (@id, @desc, @rating, @session, @log_id, @time)", conn);

            cmd.Parameters.AddWithValue("@id", messageId);
            cmd.Parameters.AddWithValue("@desc", responseText);
            cmd.Parameters.AddWithValue("@rating", rating);
            cmd.Parameters.AddWithValue("@session", _sessionId);
            cmd.Parameters.AddWithValue("@log_id", logId);
            cmd.Parameters.AddWithValue("@time", DateTime.Now);

            await cmd.ExecuteNonQueryAsync();
        }
        catch (Exception ex)
        {
            await DisplayAlert("Error", $"Failed to save rating: {ex.Message}", "OK");
        }
    }

    private async Task CopyToClipboard(string text)
    {
        try
        {
            await Clipboard.Default.SetTextAsync(text);
            await DisplayAlert("Copied", "Message copied to clipboard", "OK");
        }
        catch (Exception ex)
        {
            await DisplayAlert("Error", $"Failed to copy: {ex.Message}", "OK");
        }
    }

    private async Task ShareMessage(string text)
    {
        try
        {
            await Share.Default.RequestAsync(new ShareTextRequest
            {
                Text = text,
                Title = "CSU-Gonzaga Chat Response"
            });
        }
        catch (Exception ex)
        {
            await DisplayAlert("Error", $"Failed to share: {ex.Message}", "OK");
        }
    }

    private async void OnThemeToggleClicked(object sender, EventArgs e)
    {
        bool isDark = !ThemeManager.IsDarkMode;
        ThemeManager.ApplyTheme(isDark);

        await ThemeToggleThumb.TranslateTo(isDark ? 30 : 0, 0, 250, Easing.CubicOut);
        ThemeToggleThumb.HorizontalOptions = isDark ? LayoutOptions.End : LayoutOptions.Start;
        ThemeToggleThumb.TranslationX = 0;
        ThemeIcon.Text = isDark ? "☀️" : "🌙";
    }

    public class RasaMessage
    {
        public string Recipient_Id { get; set; }
        public string Text { get; set; }
    }
}
