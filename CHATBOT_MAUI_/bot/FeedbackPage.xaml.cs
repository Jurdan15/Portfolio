using Google.Protobuf.WellKnownTypes;
using MySql.Data.MySqlClient;
using System;

namespace bot
{
    public partial class FeedbackPage : ContentPage
    {
        private int _selectedRating = 0;
        private string _timelyResponse = "";
        private string _fullyAnswered = "";
        private Color _selectedColor = Colors.Yellow;
        private Color _unselectedColor = Colors.LightGray;
        private int _sessionId;

        public FeedbackPage(int sessionId)
        {
            InitializeComponent();
            _sessionId = sessionId;

            // Initially hide session end UI
            ChatEndedLabel.IsVisible = false;
            EndButtonsContainer.IsVisible = false;
        }

        private void OnCircleTapped(object sender, EventArgs e)
        {
            if (sender is Frame tappedCircle &&
                tappedCircle.Parent is HorizontalStackLayout container &&
                e is TappedEventArgs tappedArgs)
            {
                if (int.TryParse(tappedArgs.Parameter?.ToString(), out int rating))
                {
                    _selectedRating = rating;

                    foreach (var child in container.Children)
                        if (child is Frame circle)
                            circle.BackgroundColor = _unselectedColor;

                    for (int i = 0; i < rating; i++)
                        if (container.Children[i] is Frame circle)
                            circle.BackgroundColor = _selectedColor;
                }
            }
        }

        private void OnTimelyResponseChanged(object sender, CheckedChangedEventArgs e)
        {
            if (sender is RadioButton rb && e.Value)
                _timelyResponse = rb.Value.ToString();
        }

        private void OnFullyAnsweredChanged(object sender, CheckedChangedEventArgs e)
        {
            if (sender is RadioButton rb && e.Value)
                _fullyAnswered = rb.Value.ToString();
        }

        private async void OnSubmitFeedback(object sender, EventArgs e)
        {
            var comment = CommentsEditor.Text?.Trim();
            string connectionString = "Server=127.0.0.1;Database=inquiry;User=root;Password=;";

            using var connection = new MySqlConnection(connectionString);
            try
            {
                await connection.OpenAsync();

                string feedbackQuery = @"INSERT INTO feedback 
(rating, timely_response, fully_answered, comments, timestamp, session_id)
VALUES (@rating, @timely, @answered, @comments, @timestamp, @session_id)";
                using var feedbackCmd = new MySqlCommand(feedbackQuery, connection);
                feedbackCmd.Parameters.AddWithValue("@rating", _selectedRating);
                feedbackCmd.Parameters.AddWithValue("@timely", _timelyResponse);
                feedbackCmd.Parameters.AddWithValue("@answered", _fullyAnswered);
                feedbackCmd.Parameters.AddWithValue("@comments", comment ?? string.Empty);
                feedbackCmd.Parameters.AddWithValue("@timestamp", DateTime.UtcNow);
                feedbackCmd.Parameters.AddWithValue("@session_id", _sessionId);
                await feedbackCmd.ExecuteNonQueryAsync();

                string sessionQuery = @"INSERT INTO chatsessions (session_time, session_desc) 
VALUES (@session_time, @session_desc)";
                using var sessionCmd = new MySqlCommand(sessionQuery, connection);
                sessionCmd.Parameters.AddWithValue("@session_time", DateTime.UtcNow);
                sessionCmd.Parameters.AddWithValue("@session_desc", "User has left the chat");
                await sessionCmd.ExecuteNonQueryAsync();

                ChatEndedLabel.Text = $"The visitor has left the chat.\nThe chat ended. ({DateTime.Now:h:mm tt})";
                ChatEndedLabel.IsVisible = true;
                EndButtonsContainer.IsVisible = true;

                await DisplayAlert("Thank you!", $"Your rating: {_selectedRating}\nFeedback has been submitted.", "OK");
            }
            catch (Exception ex)
            {
                await DisplayAlert("Error", $"Failed to submit feedback: {ex.Message}", "OK");
            }
        }

        private async void OnRestartChat(object sender, EventArgs e)
        {
            await Navigation.PushAsync(new MainPage(_sessionId));
        }

        private void OnExitButtonClicked(object sender, EventArgs e)
        {
#if WINDOWS || MACCATALYST || IOS
            System.Diagnostics.Process.GetCurrentProcess().Kill();
#elif ANDROID
            Android.OS.Process.KillProcess(Android.OS.Process.MyPid());
#else
            System.Diagnostics.Process.GetCurrentProcess().Kill();
#endif
        }
    }
}
