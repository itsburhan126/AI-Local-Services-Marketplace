class GigFaqModel {
  final int? id;
  final int? gigId;
  final String question;
  final String answer;

  GigFaqModel({
    this.id,
    this.gigId,
    required this.question,
    required this.answer,
  });

  factory GigFaqModel.fromJson(Map<String, dynamic> json) {
    return GigFaqModel(
      id: json['id'],
      gigId: json['gig_id'],
      question: json['question'] ?? '',
      answer: json['answer'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {'id': id, 'gig_id': gigId, 'question': question, 'answer': answer};
  }
}
