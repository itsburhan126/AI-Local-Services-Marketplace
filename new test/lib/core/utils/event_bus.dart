import 'dart:async';
import 'package:pusher_channels_flutter/pusher_channels_flutter.dart';

class GlobalEventBus {
  static final StreamController<PusherEvent> _pusherStream = StreamController.broadcast();
  static Stream<PusherEvent> get pusherStream => _pusherStream.stream;
  static void emitPusherEvent(PusherEvent event) => _pusherStream.add(event);
}
